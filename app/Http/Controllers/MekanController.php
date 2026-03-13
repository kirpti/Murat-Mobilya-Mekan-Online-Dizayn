<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;

class MekanController extends Controller
{
    /**
     * Ana sayfa — Blade view'ı döner.
     */
    public function index()
    {
        return view('mekan.index');
    }

    /**
     * AI tasarım önerisi — Anthropic API'ye server-side istek atar.
     * API anahtarı hiçbir zaman tarayıcıya gönderilmez.
     */
    public function aiSuggest(Request $request): JsonResponse
    {
        $request->validate([
            'roomType'      => 'required|string|max:50',
            'roomW'         => 'required|numeric|min:50|max:3000',
            'roomD'         => 'required|numeric|min:50|max:3000',
            'roomH'         => 'required|numeric|min:150|max:700',
            'style'         => 'required|string|max:50',
            'colorPalette'  => 'required|string|max:50',
            'furniture'     => 'required|string|max:2000',
            'notes'         => 'nullable|string|max:500',
        ]);

        $roomLabels = [
            'salon'   => 'Salon',
            'yatak'   => 'Yatak Odası',
            'yemek'   => 'Yemek Odası',
            'calisma' => 'Çalışma Odası',
            'mutfak'  => 'Mutfak',
            'banyo'   => 'Banyo',
        ];

        $roomLabel = $roomLabels[$request->roomType] ?? $request->roomType;
        $notes     = $request->notes ? "Kullanıcı notları: {$request->notes}" : '';

        $prompt = <<<PROMPT
Sen bir iç mekân tasarım uzmanısın. Kısa ve somut öneriler ver.

Oda: {$roomLabel}
Boyutlar: {$request->roomW} × {$request->roomD} cm, tavan yüksekliği: {$request->roomH} cm
Stil: {$request->style}, Renk paleti: {$request->colorPalette}
Seçilen mobilyalar (sunta/MDF/ahşap): {$request->furniture}
{$notes}

Şunları sağla:
1. Yerleştirme mantığı (2-3 cümle)
2. En önemli 2-3 tasarım önerisi (kısa maddeler halinde)
3. Uyarı varsa belirt (dar geçit, ergonomi, geçiş mesafeleri vb.)

Türkçe cevap ver, tasarımcı diliyle ama anlaşılır şekilde.
PROMPT;

        try {
            $response = Http::withHeaders([
                'x-api-key'         => config('services.anthropic.key'),
                'anthropic-version' => '2023-06-01',
                'content-type'      => 'application/json',
            ])->post('https://api.anthropic.com/v1/messages', [
                'model'      => 'claude-sonnet-4-20250514',
                'max_tokens' => 1000,
                'messages'   => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            if ($response->successful()) {
                $text = $response->json('content.0.text', 'Yanıt alınamadı.');
                return response()->json(['success' => true, 'text' => $text]);
            }

            return response()->json([
                'success' => false,
                'text'    => 'API yanıt hatası: ' . $response->status(),
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'text'    => 'Sunucu hatası oluştu. Lütfen tekrar deneyin.',
            ], 500);
        }
    }
}
