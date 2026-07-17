<?php

namespace App\Services\Settings;

use App\Contracts\SettingsTabHandler;
use App\Models\User;
use Illuminate\Support\Facades\Redis;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Carbon;

class PrivacyTabHandler implements SettingsTabHandler
{
    public function handleShow(User $user): array
    {
        $currentSessionId = session()->getId();

        $redis = Redis::connection(config('session.connection', 'session'));

        $sessionDatabaseNum = config('database.redis.session.database', 2);
        $redis->select($sessionDatabaseNum);

        $keys = [];
        $cursor = '0';
        do {
            $result = $redis->scan($cursor, [
                'match' => '*',
                'count' => 100
            ]);

            $cursor = $result[0] ?? '0';
            $fetchedKeys = $result[1] ?? [];
            $keys = array_merge($keys, $fetchedKeys);

        } while ($cursor !== '0' && count($keys) < 5000);

        $keys = array_unique($keys);

        $sessions = collect($keys)->map(function ($key) use ($redis, $currentSessionId, $user) {
            $globalPrefix = config('database.redis.options.prefix', '');
            $redisKey = str_replace($globalPrefix, '', $key);

            $rawPayload = $redis->get($redisKey);
            if (!$rawPayload) {
                return null;
            }

            $sessionData = null;

            if (is_string($rawPayload)) {
                if (preg_match('/^s:\d+:"(.*)";$/s', $rawPayload, $matches)) {
                    $jsonString = stripslashes($matches[1]);
                    $sessionData = json_decode($jsonString, true);
                } else {
                    $unserialized = @unserialize($rawPayload, ['allowed_classes' => false]);
                    if ($unserialized === false) {
                        $unserialized = $rawPayload;
                    }
                    $sessionData = is_string($unserialized) ? json_decode($unserialized, true) : $unserialized;
                }
            }

            if (!is_array($sessionData) || !isset($sessionData['ip_address'])) {
                return null;
            }

            $authUserId = null;
            foreach ($sessionData as $dataKey => $dataValue) {
                if (str_starts_with($dataKey, 'login_web_')) {
                    $authUserId = $dataValue;
                    break;
                }
            }

            if ((int) $authUserId !== (int) $user->id) {
                return null;
            }

            $parts = explode(':', $redisKey);
            $sessionId = end($parts);

            $userAgentString = $sessionData['user_agent'] ?? '';
            $agent = new Agent();
            if ($userAgentString) {
                $agent->setUserAgent($userAgentString);
            }

            $platform = $agent->platform();
            if (str_contains($userAgentString, 'Macintosh')) {
                $platform = 'Macintosh';
            }

            return [
                'id' => $sessionId,
                'ip_address' => $sessionData['ip_address'] ?? 'Unknown IP',
                'is_current_device' => $sessionId === $currentSessionId,
                'browser' => $agent->browser() ?: 'Unknown Browser',
                'platform' => $platform ?: 'Unknown OS',
                'last_active' => isset($sessionData['last_activity'])
                    ? Carbon::createFromTimestamp($sessionData['last_activity'])->diffForHumans()
                    : 'Just now',
                'last_activity_timestamp' => $sessionData['last_activity'] ?? now()->timestamp
            ];
        })
            ->filter()
            ->sortByDesc('last_activity_timestamp')
            ->values()
            ->toArray();

        return ['sessions' => $sessions];
    }


    public function handleUpdate(User $user, array $data): void
    {
        unset($data['current_tab']);
        $user->saveSettings($data);
    }
}
