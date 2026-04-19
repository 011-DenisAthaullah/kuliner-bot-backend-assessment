<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TelegramService;
use App\Services\RestaurantService;

class TelegramController extends Controller
{
    public function __construct(
        protected TelegramService $telegram,
        protected RestaurantService $restaurant
    ) {}

    public function webhook(Request $request)
    {
        $update = $request->all();

        $message = $update['message'] ?? null;
        if (!$message) {
            return response()->json(['ok' => true]);
        }

        $chatId = $message['chat']['id'];

        // ======================
        // 1. LOCATION MESSAGE
        // ======================
        if (isset($message['location'])) {
            $query = "jakarta";

            $data = $this->restaurant->search($query);

            if (isset($data['error'])) {
                $this->telegram->sendMessage(
                    $chatId,
                    "📍 Lokasi diterima, tapi data restoran tidak ditemukan"
                );

                return response()->json(['ok' => true]);
            }

            $result = "📍 Restoran di sekitar kamu:\n\n";

            foreach ($data as $i => $r) {
                $result .= ($i + 1) . ". {$r['name']}\n";
                $result .= "📍 {$r['address']}\n";
                $result .= "⭐ {$r['rating']}\n\n";
            }

            $this->telegram->sendMessage($chatId, $result);

            return response()->json(['ok' => true]);
        }

        // ======================
        // 2. CONTACT MESSAGE
        // ======================
        if (isset($message['contact'])) {
            $this->telegram->sendMessage(
                $chatId,
                "📞 Kontak diterima: " . $message['contact']['phone_number']
            );

            return response()->json(['ok' => true]);
        }

        // ======================
        // 3. TEXT MESSAGE
        // ======================
        $text = $message['text'] ?? '';

        if (str_starts_with($text, '/search')) {
            $query = trim(str_replace('/search', '', $text));

            if (!$query) {
                $this->telegram->sendMessage(
                    $chatId,
                    "Gunakan: /search jakarta"
                );

                return response()->json(['ok' => true]);
            }

            $data = $this->restaurant->search($query);

            if (isset($data['error'])) {
                $this->telegram->sendMessage(
                    $chatId,
                    "Data tidak ditemukan"
                );

                return response()->json(['ok' => true]);
            }

            $result = "🍜 Hasil Restoran:\n\n";

            foreach ($data as $r) {
                $result .= "• {$r['name']}\n";
                $result .= "{$r['address']}\n";
                $result .= "⭐ {$r['rating']}\n\n";
            }

            $this->telegram->sendMessage($chatId, $result);

            return response()->json(['ok' => true]);
        }

        // ======================
        // DEFAULT RESPONSE
        // ======================
        $this->telegram->sendMessage(
            $chatId,
            "Kirim /search, lokasi, atau kontak"
        );

        return response()->json(['ok' => true]);
    }
}