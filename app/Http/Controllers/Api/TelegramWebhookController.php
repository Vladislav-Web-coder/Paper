<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Получаем тело сообщения от Telegram
        $update = $request->all();

        if (!isset($update['message']['text'])) {
            return response()->json(['status' => 'success']);
        }

        $chatId = $update['message']['chat']['id'];
        $text = $update['message']['text'];

        if (Str::startsWith($text, '/start')) {
            $token = trim(Str::replaceFirst('/start', '', $text));

            if (!empty($token)) {
            $user = User::where('telegram_verification_token', $token)->first();

                if ($user) {
                    $user->update([
                        'telegram_chat_id' => $chatId,
                        'telegram_verification_token' => null,
                    ]);

                    $this->sendTelegramMessage($chatId, "🎉 Аккаунт успешно привязан к сайту {$user->name}!");
                }
            }
        }
        return response()->json(['status' => 'success']);
    }
    private function sendTelegramMessage($chatId, $text)
    {
        $token = config('services.telegram-bot-api.token') ?? env('TELEGRAM_BOT_TOKEN');
        $url = "https://telegram.org/{$token}/sendMessage";

        file_get_contents($url . '?' . http_build_query([
            'chat_id' => $chatId,
            'text' => $text,
        ]));
    }

    public function connect(Request $request)
    {
        $user = auth()->user();

        $token = Str::random(32);
        $user->telegram_verification_token = $token;
        $user->save();

        $botName = 'your_paper_bot'; // Укажите имя вашего бота БЕЗ @

        // Редирект в мессенджер с токеном
        return redirect()->away("https://t.me/{$botName}?start={$token}");
    }
}
