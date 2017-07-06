<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboTicker.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboTicker extends XiboEntity
{
    public $widgetId;
    public $playlistId;
    public $ownerId;
    public $type;
    public $duration;
    public $displayOrder;
    public $useDuration;
    public $calculatedDuration;
    public $widgetOptions;
    public $mediaIds;
    public $audio;
    public $permissions;
    public $module;
    public $name;
    public $uri;
    public $effect;
    public $speed;
    public $backgroundColor;
    public $noDataMessage;
    public $dateFormat;
    public $updateInterval;
    public $templateId;
    public $durationIsPerItem;
    public $itemsPerPage;
    public $javaScript;
    public $overrideTemplate;
    public $copyright;
    public $numItems;
    public $takeItemsFrom;
    public $itemsSideBySide;
    public $upperLimit;
    public $lowerLimit;
    public $allowedAttributes;
    public $stripTags;
    public $disableDateSort;
    public $textDirection;
    public $dataSetId;
    public $filter;
    public $ordering;
    public $useOrderingClause;
    public $useFilteringClause;

    /**
     * Get by Id
     * @param $id
     * @return $this|XiboTicker
     * @throws XiboApiException
     */
    public function getById($id)
    {
        $response = $this->doGet('/playlist/widget', [
            'playlistId' => $id
        ]);

        return clone $this->hydrate($response[0]);
    }

    /**
     * Create
     * @param $sourceId
     * @param $uri
     * @param $duration
     * @param $useDuration
     * @param $dataSetId
     * @param $playlistId
     */
    public function create($sourceId, $uri, $dataSetId, $duration, $useDuration, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->sourceId = $sourceId;
        $this->uri = $uri;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->dataSetId = $dataSetId;
        $this->playlistId = $playlistId;
        $response = $this->doPost('/playlist/widget/ticker/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
    * Delete
    */
    public function delete()
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $response = $this->doDelete('/playlist/widget/' . $this->widgetId , $this->toArray());

        return true;
    }
}
