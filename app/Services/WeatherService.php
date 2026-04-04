<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    public function getWeather($city)
    {
        try {
            $apiKey = env('OPENWEATHER_API_KEY');
            
            if (empty($apiKey)) {
                Log::error('OpenWeather API key is not set');
                return ['error' => 'API key not configured'];
            }
            
            $response = Http::timeout(10)->get("https://api.openweathermap.org/data/2.5/weather", [
                'q' => $city,
                'appid' => $apiKey,
                'units' => 'metric',
            ]);
            
            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Weather API error: ' . $response->body());
                return [
                    'cod' => $response->status(),
                    'message' => $response->json('message') ?? 'City not found'
                ];
            }
            
        } catch (\Exception $e) {
            Log::error('Weather Service Exception: ' . $e->getMessage());
            return [
                'cod' => 500,
                'message' => 'Unable to fetch weather data'
            ];
        }
    }
}
