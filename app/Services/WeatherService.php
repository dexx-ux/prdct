<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.openweathermap.org/data/2.5/weather';

    public function __construct()
    {
        $this->apiKey = config('services.openweather.key', env('OPENWEATHER_API_KEY', ''));
    }

    public function getWeather($city)
    {
        if (empty($this->apiKey)) {
            Log::warning('OpenWeather API key is missing. Please add OPENWEATHER_API_KEY to your .env file');
            return ['cod' => 401, 'message' => 'API key not configured'];
        }

        try {
            $response = Http::timeout(10)->get($this->baseUrl, [
                'q' => $city,
                'appid' => $this->apiKey,
                'units' => 'metric'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Weather data fetched successfully for: ' . $city);
                return $data;
            }

            Log::error('Weather API error: ' . $response->status() . ' - ' . $response->body());
            return ['cod' => $response->status(), 'message' => 'Failed to fetch weather data'];
            
        } catch (\Exception $e) {
            Log::error('Weather service exception: ' . $e->getMessage());
            return ['cod' => 500, 'message' => 'Service error: ' . $e->getMessage()];
        }
    }
}