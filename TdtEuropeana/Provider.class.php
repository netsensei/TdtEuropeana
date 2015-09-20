<?php

require('TdtEuropeana.class.php');

use Colada\Europeana\Payload\ProviderPayload;
use Colada\Europeana\Payload\PayloadResponseInterface;

/**
 * Returns information about the configured provider.
 *
 * A provider should be configured in app/config/tdteuropeana.php. The
 * providerId config property needs to be set. To find the identifier of the
 * data provider, perform a call to http://europeana.eu/api/v2/providers.json.
 *
 * Alternatively, you could create a TDT API call using the providers.class.php
 * This will return a list of all the providers.
 *
 * @see http://europeana.eu/api/v2/providers.json
 */
class Provider extends TdtEuropeana {

    /**
     * {@inheritdoc}
     */
    public static function getParameters() {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getPayload() {
        $payload = new ProviderPayload();
        $providerId = \Config::get('tdteuropeana.providerId');

        $payload->setProviderId($providerId);

        return $payload;
    }

    /**
     * {@inheritdoc}
     */
    public function parseResponse(PayloadResponseInterface $payloadResponse) {
        $rows = array();
        if ($payloadResponse->getTotalResults() > 0) {
            $items = $payloadResponse->getItems()->toArray();
            foreach ($items as $item) {
                $rows[] = array(
                    'identifier' => $item->getIdentifier(),
                    'country' => $item->getCountry(),
                    'name' => $item->getName(),
                    'acronym' => $item->getAcronym(),
                    'altName' => $item->getAltName(),
                    'scope' => $item->getScope(),
                    'domain' => $item->getDomain(),
                    'geolevel' => $item->getGeoLevel(),
                    'role' => $item->getRole(),
                    'website' => $item->getWebsite(),
                );
            }
        }
        return $rows;
    }
}
