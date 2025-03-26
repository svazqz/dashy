<?php

namespace Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Weather
{
    private $client;
    private $apiKey;
    private $lat;
    private $lon;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = '6224f7ef716985d129aeb124bd0fbab8';
        $this->lat = '21.935260';
        $this->lon = '-102.274391';
    }

    public function getWeather()
    {
        try {
            $response = $this->client->get('https://api.openweathermap.org/data/2.5/weather', [
                'query' => [
                    'lat' => $this->lat,
                    'lon' => $this->lon,
                    'appid' => $this->apiKey,
                    'units' => 'metric'
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            return [
                'temperature' => round($data['main']['temp']),
                'icon' => 'http://openweathermap.org/img/wn/' . $data['weather'][0]['icon'] . '@2x.png',
                'description' => $data['weather'][0]['description']
            ];

        } catch (GuzzleException $e) {
            return [
                'temperature' => '--',
                'icon' => null,
                'description' => 'Weather unavailable'
            ];
        }
    }
}