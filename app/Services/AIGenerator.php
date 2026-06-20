<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIGenerator
{
    public static function generateFields(string $prompt): array
    {
        $apiKey = config('services.gemini.api_key');
        if (!$apiKey) {
            return self::fallbackFields($prompt);
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=$apiKey";

        try {
            $response = Http::post($url, [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => "Generate form fields for: $prompt. Return only a JSON array of objects with keys: type, label, placeholder, options, required. Valid types: text, textarea, email, phone, dropdown, radio, checkbox, file, date, rating, signature."
                            ],
                        ],
                    ],
                ],
            ]);

            if ($response->successful()) {
                $raw = $response->json('candidates.0.content.parts.0.text');
                $cleaned = preg_replace('/```json|```/', '', trim($raw));
                $fields = json_decode($cleaned, true);
                if (is_array($fields) && count($fields) > 0) {
                    return $fields;
                }
            }
        } catch (\Exception $e) {
            Log::error('Gemini API error: ' . $e->getMessage());
        }

        return self::fallbackFields($prompt);
    }

    private static function fallbackFields(string $prompt): array
    {
        return [
            ['type' => 'text', 'label' => 'Name', 'placeholder' => 'Your name', 'required' => true],
            ['type' => 'email', 'label' => 'Email', 'placeholder' => 'you@example.com', 'required' => true],
            ['type' => 'textarea', 'label' => 'Message', 'placeholder' => 'Tell us...', 'required' => false],
        ];
    }
}