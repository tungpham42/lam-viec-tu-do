<?php

namespace Drupal\random_quote;

class Quote implements QuoteInterface {
    public function getQuote() {
	    try {
            $url = 'https://movies-quotes.p.rapidapi.com/quote';
            $client = \Drupal::httpClient();
            $response = $client->request('GET', $url, [
                'headers' => [
                    'X-RapidAPI-Host' => 'movies-quotes.p.rapidapi.com',
                    'X-RapidAPI-Key' => 'OvEezA3997msh66qZgNJ66YsAWs0p13mlOMjsnO4L2P0BG7sM4',
                ]
            ]);
            $body = $response->getBody()->getContents();
            $status = $response->getStatusCode();
            $result = json_decode($body, true);
            return $result;
        }
        catch (RequestException $e) {
            return FALSE;
        }
	}
}