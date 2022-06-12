<?php

namespace Drupal\random_quote\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class Quote implements QuoteInterface {

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
     * The JSON body name.
     *
     * @var array|mixed|null
     */
    protected $bodyName;

    /**
     * Constructs a RandomQuoteManager object.
     *
     * @param \GuzzleHttp\ClientInterface $http_client
     *   The HTTP client.
     * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
     *   The config factory.
     */
    public function __construct(ClientInterface $http_client, ConfigFactoryInterface $config_factory) {
        $this->httpClient = $http_client;
        $ramdomQuoteSettings = $config_factory->get('random_quote.settings');
        $this->header = [
            'x-rapidapi-key' => $ramdomQuoteSettings->get('x_rapidapi_key'),
            'x-rapidapi-host' => $ramdomQuoteSettings->get('x_rapidapi_host'),
        ];
        $this->url = $ramdomQuoteSettings->get('url');
        $this->bodyName = $ramdomQuoteSettings->get('body_name');
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

    public function getBodyName() {
        return $this->bodyName;
    }
}