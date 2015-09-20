<?php

require('TdtEuropeana.class.php');

use Colada\Europeana\Payload\SearchPayload;
use Colada\Europeana\Payload\DatasetsPayload;
use Colada\Europeana\Payload\Facet\Refinement;
use Colada\Europeana\Payload\PayloadResponseInterface;

/**
 * Returns information about all the datasets associated with the configured
 * data provider. If passed a dataset identifier, returns all the records
 * associated with the specified dataset.
 */
class ProviderDataset extends TdtEuropeana {

    /**
     * {@inheritdoc}
     */
    public static function getParameters() {
        return array(
            'collectionName' => array(
                'required' => FALSE,
                'description' => 'The collection name ID',
            ),
            'page' => array(
                'required' => FALSE,
                'description' => 'Allows pagination through the dataset'
            )
        );
    }

    /**
     * Lists all the datasets for the configured provider.
     *
     * @return DatasetsPayload Payload to retrieve all the datasets.
     */
    public function listDatasets() {
        $payload = new DatasetsPayload();
        $providerId = \Config::get('tdteuropeana.providerId');
        $payload->setProviderId($providerId);
        return $payload;
    }

    /**
     * Parses the response.
     *
     * Lists all the datasets associated with the configured data provider.
     *
     * @param PayloadResponseInterface DatasetsPayloadResponse instance.
     * @return array An associative array of parsed data.
     */
    public function listDatasetsResponse($payloadResponse) {
       $rows = array();
        if ($payloadResponse->getTotalResults() > 0) {
            $items = $payloadResponse->getItems()->toArray();
            foreach ($items as $item) {
                $rows[] = array(
                    'identifier' => $item->getIdentifier(),
                    'provIdentifier' => $item->getProvIdentifier(),
                    'providerName' => $item->getProviderName(),
                    'edmDatasetName' => $item->getEdmDatasetName(),
                    'status' => $item->getStatus(),
                    'publishedRecords' => $item->getPublishedRecords(),
                    'creationDate' => $item->getCreationDate(),
                    'detailUrl' => url('europeana/provider/dataset/' . $item->getEdmDatasetName()),
                );
            }
        }
        return $rows;
    }

    /**
     * Lists all the records asociated with a single dataset id.
     *
     * We use SearchPayload instead of DatasetPayload because this call will
     * return data directly from the Lucene index used on the Europeana server
     * side.
     *
     * @return SearchPayload The SearchPayload which will retrieve the data.
     */
    public function listRecords() {
        $payload = new SearchPayload();

        $payload->setQuery('*:*');
        $refinement = new Refinement('europeana_collectionName', $this->collectionName);
        $payload->addRefinement($refinement);

        $rows = 20;
        $this->page = (isset($this->page)) ? $this->page : 1;

        $offset = $this->page * $rows;
        $payload->setStart($offset);
        $payload->setRows($rows);

        return $payload;
    }

    /**
     * Parses the response.
     *
     * Lists all the records associated with the specified dataset id
     *
     * @param PayloadResponseInterface SearchPayloadResponse instance.
     * @return array An associative array of parsed data.
     */
    public function listRecordsResponse(PayloadResponseInterface $payloadResponse) {
        $rows = array();
        if ($payloadResponse->getTotalResults() > 0) {
            $items = $payloadResponse->getItems()->toArray();
            foreach ($items as $item) {
                $rows[] = array(
                    'title' => $item->getTitle()->get(0),
                    'type' => $item->getType(),
                    'creator' => (!is_null($item->getDcCreator())) ? $item->getDcCreator()->get(0) : '',
                    'dataProvider' => $item->getDataProvider()->get(0),
                    'edmIsShownAt' => $item->getEdmIsShownAt()->get(0),
                    'rights' => $item->getRights()->get(0),
                );
            }
        }
        return $rows;
    }

    /**
     * {@inheritdoc}
     */
    public function getPayload() {
        return (!isset($this->collectionName)) ? $this->listDatasets() : $this->listRecords();
    }

    /**
     * {@inheritdoc}
     */
    public function parseResponse(PayloadResponseInterface $payloadResponse) {
        return (!isset($this->collectionName)) ? $this->listDatasetsResponse($payloadResponse) : $this->listRecordsResponse($payloadResponse);
    }
}