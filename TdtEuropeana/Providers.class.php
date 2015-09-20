<?php

require('Provider.class.php');

use Colada\Europeana\Payload\ProvidersPayload;
use Colada\Europeana\Payload\PayloadResponseInterface;

class Providers extends Provider {
    public function getPayload() {
        $payload = new ProvidersPayload();
        return $payload;
    }
}
