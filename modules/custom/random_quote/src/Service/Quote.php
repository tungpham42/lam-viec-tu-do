<?php

namespace Drupal\random_quote\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Messenger\MessengerInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class Quote {

    /**
     * The HTTP client.
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;
  
    /**
     * The Http Header.
     *
     * @var array
     */
    protected $header;
  
    /**
     * The HTTP Url.
     *
     * @var array|mixed|null
     */
    protected $url;
  
    /**
     * The messenger service.
     *
     * @var \Drupal\Core\Messenger\MessengerInterface
     */
    protected $messenger;

    /**
     * Constructs a RandomQuoteManager object.
     *
     * @param \GuzzleHttp\ClientInterface $http_client
     *   The HTTP client.
     * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
     *   The config factory.
     * @param \Drupal\Core\Messenger\MessengerInterface $messenger
     *   The messenger service.
     */
    public function __construct(ClientInterface $http_client, ConfigFactoryInterface $config_factory, MessengerInterface $messenger) {
        $this->httpClient = $http_client;
        $this->messenger = $messenger;
        $ramdomQuoteSettings = $config_factory->get('random_quote.settings');
        $this->header = [
            'x-rapidapi-key' => $ramdomQuoteSettings->get('x_rapidapi_key'),
            'x-rapidapi-host' => $ramdomQuoteSettings->get('x_rapidapi_host'),
        ];
        $this->url = $ramdomQuoteSettings->get('url');
    }

    /**
     * Test Connection of API.
     */
    public function testResponse() {
        $param['headers'] = $this->header;
        try {
        $response = $this->httpClient->request('GET', $this->url, $param);
        if ($response->getStatusCode() === 200) {
            return TRUE;
        }
        }
        catch (GuzzleException $e) {
        }
        return FALSE;
    }

    /**
     * Get Body of response.
     *
     * @return string|null
     *   Json response of Quote.
     */
    public function getContentResponse() {
        $param['headers'] = $this->header;
        try {
            $response = $this->httpClient->request('GET', $this->url, $param);
            if ($response->getStatusCode() === 200) {
                return $response->getBody()->getContents();
            }
        }
        catch (GuzzleException $e) {
        }
        return NULL;
    }
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