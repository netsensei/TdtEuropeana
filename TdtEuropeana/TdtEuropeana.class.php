<?php

/**
 * Installed Resource for Europeana
 * @see http://www.europeana.eu
 * @see http://labs.europeana.eu
 */

use GuzzleHttp\Client;
use Colada\Europeana\Transport\ApiClient;
use Colada\Europeana\Payload\PayloadResponseInterface;
use Colada\Europeana\Payload\PayloadInterface;

/**
 * Abstract TdtEuropeana class
 */
abstract class TdtEuropeana {

    /**
     * Returns a payload object.
     *
     * The payload class contains all the parameters that will be send to the
     * Europeana API. Depending on the payload type, different API calls can be
     * made. ie. SearchPayload will yield a call to search.json will ProviderPayload
     * yields a call to provider.json.
     *
     * @return Colada\Europeana\Payload\PayloadInterface
     */
    abstract public function getPayload();

    /**
     * Parse the response of the API
     *
     * Parse and process the response. Fetch the data you want from the
     * PayloadResponseInterface object and return an associative array. The
     * Datatank will show this array.
     *
     * @param PayloadResponseInterface $payloadResponse
     *
     * @return array An associative array of values.
     */
    abstract public function parseResponse(PayloadResponseInterface $payloadResponse);

    /**
     * Set parameters as class properties, defined in the getParameters()
     * method.
     */
    public function setParameter($key, $value){
        $parameters = $this::getParameters();
        if (array_key_exists($key, $parameters)) {
            $this->$key = $value;
        }
    }

    /**
     * Makes an API call, handles errors and returns the response.
     *
     * @return array An associative array of processed data.
     */
    public function getData() {
        $data = array();
        try {
            $apiKey = \Config::get('tdteuropeana.apiKey');
            $client = new Client();
            $apiClient = new ApiClient($apiKey, $client);

            $payload = $this->getPayload();

            $payloadResponse = $apiClient->send($payload);

            $data = $this->parseResponse($payloadResponse);
        } catch (EuropeanaException $e) {
            // throw an error
        }

        return $data;
    }
}
