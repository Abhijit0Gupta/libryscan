<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key', env('GEMINI_API_KEY'));
    }

    public function generateAssetSummary(string $titleOrCode): ?string
    {
        if (!$this->apiKey) {
            return 'AI Key not configured in .env file.';
        }

        $prompt = "You are an internal IT asset librarian. Provide a concise 2-sentence technical summary and recommended location/usage notes for this item: '{$titleOrCode}'. Keep it professional for an IT company.";

        // Array of active endpoints
        $models = [
            'gemini-3.8-flash',
            'gemini-3.5-flash-lite',
        ];

        foreach ($models as $model) {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$this->apiKey}";

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                return $response->json('candidates.0.content.parts.0.text');
            }

            Log::warning("Gemini model {$model} failed: " . $response->body());
        }

        return null;
    }
}