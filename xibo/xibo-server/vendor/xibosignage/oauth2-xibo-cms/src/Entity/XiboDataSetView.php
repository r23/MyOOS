<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboDataSetView.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboDataSetView extends XiboEntity
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
    public $dataSetId;
    public $updateInterval;
    public $rowsPerPage;
    public $showHeadings;
    public $upperLimit;
    public $lowerLimit;
    public $filter;
    public $ordering;
    public $templateId;
    public $overrideTemplate;
    public $useOrderingClause;
    public $useFilteringClause;
    public $noDataMessage;

    /**
     * Get by Id
     * @param $id
     * @return $this|XiboDataSetView
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
     * @param $name
     * @param $dataSetId
     */
    public function create($name, $dataSetId, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->dataSetId = $dataSetId;
        $this->playlistId = $playlistId;
        $response = $this->doPost('/playlist/widget/dataSetView/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }
    
    /**
     * Edit
     * @param $dataSetColumnId
     * @param $name
     * @param $duration
     * @param $updateInterval
     * @param $showHeadings
     * @param $upperLimit
     * @param $lowerLimit
     * @param $filter
     * @param $ordering
     * @param $templateId
     * @param $overrideTemplate
     * @param $useOrderingClause
     * @param $useFilteringClause
     * @param $noDataMessage
     * @param $dataSetId
     */
    public function edit($dataSetColumnId, $name, $duration, $useDuration, $updateInterval, $rowsPerPage, $showHeadings, $upperLimit, $lowerLimit, $filter, $ordering, $templateId, $overrideTemplate, $useOrderingClause, $useFilteringClause, $noDataMessage, $widgetId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->dataSetColumnId = $dataSetColumnId;
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->updateInterval = $updateInterval;
        $this->rowsPerPage = $rowsPerPage;
        $this->showHeadings = $showHeadings;
        $this->upperLimit = $upperLimit;
        $this->lowerLimit = $lowerLimit;
        $this->filter = $filter;
        $this->ordering = $ordering;
        $this->templateId = $templateId;
        $this->overrideTemplate = $overrideTemplate;
        $this->useOrderingClause = $useOrderingClause;
        $this->useFilteringClause = $useFilteringClause;
        $this->noDataMessage = $noDataMessage;
        $this->widgetId = $widgetId;
        $response = $this->doPut('/playlist/widget/' . $widgetId, $this->toArray());

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
