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
        $agent = new Agent();
        $currentSessionId = session()->getId();

        $redis = Redis::connection(config('session.connection', 'default'));
        $keys = $redis->keys('*paper:*');

        $sessions = collect($keys)->map(function ($key) use ($redis, $agent, $currentSessionId, $user) {
            $globalPrefix = config('database.redis.options.prefix', '');
            $redisKey = str_replace($globalPrefix, '', $key);

            $rawPayload = $redis->get($redisKey);
            if (!$rawPayload) {
                return null;
            }

            $sessionData = null;

            if (is_string($rawPayload)) {
                $unserialized = @unserialize($rawPayload);
                if ($unserialized === false) {
                    $unserialized = @unserialize(@unserialize($rawPayload));
                }

                if (is_string($unserialized)) {
                    $sessionData = json_decode($unserialized, true);
                } elseif (is_array($unserialized)) {
                    $sessionData = $unserialized;
                }
            }

            if (!is_array($sessionData)) {
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
            $agent->setUserAgent($userAgentString);

            $browser = $userAgentString ? $agent->browser() : 'Unknown Browser';
            $platform = $userAgentString ? $agent->platform() : 'Unknown OS';

            return [
                'id' => $sessionId,
                'ip_address' => $sessionData['ip_address'] ?? 'Unknown IP',
                'is_current_device' => $sessionId === $currentSessionId,
                'browser' => $browser,
                'platform' => $platform,
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
