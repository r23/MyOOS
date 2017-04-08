<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboSchedule.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;
class XiboSchedule extends XiboEntity
{
	private $url = '/schedule';
    private $url2 = '/schedule/data/events';
	public $eventId;
	public $eventTypeId;
	public $toDt;
	public $fromDt;
	public $isPriority;
	public $displayOrder;
	public $title;
	public $campaign;
	public $campaignId;
	public $command;
	public $commandId;
	public $displayGroupId;
	public $userId;
	public $recurrenceType;
	public $recurrenceDetail;
	public $recurrenceRange;
	public $dayPartId;

 	/**
     * @param array $params
     * @return array|XiboSchedule
     */
    public function get(array $params = [])
    {
        $entries = [];
        $response = $this->doGet($this->url2, $params);

        foreach ($response as $item) {
            $entries[] = clone $this->hydrate($item);
        }

        return $entries;
    }

    /**
     * @param $id
     * @return XiboSchedule
     * @throws XiboApiException
     */
    public function getById($id)
    {
        $response = $this->doGet($this->url2, [
            'eventId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return clone $this->hydrate($response[0]);
    }

    /**
     * Create Campaign/Layout event
     * @param $scheduleType
     * @param $scheduleFrom
     * @param $scheduleTo
     * @param $scheduleCampaignId
     * @param $scheduleDisplays
     * @param $scheduledayPartId
     * @param $scheduleRecurrenceType
     * @param $scheduleRecurrenceDetail
     * @param $scheduleRecurrenceRange
     * @param $scheduleOrder
     * @param $scheduleIsPriority
     * @return XiboSchedule
     */
    public function createEventLayout($scheduleFrom, $scheduleTo, $scheduleCampaignId, $scheduleDisplays, $scheduledayPartId, $scheduleRecurrenceType, $scheduleRecurrenceDetail, $scheduleRecurrenceRange, $scheduleOrder, $scheduleIsPriority)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->eventTypeId = 1;
        $this->fromDt = $scheduleFrom;
        $this->toDt = $scheduleTo;
        $this->campaignId = $scheduleCampaignId;
        $this->displayGroupIds = $scheduleDisplays;
        $this->dayPartId = $scheduledayPartId;
        $this->recurrenceType = $scheduleRecurrenceType;
        $this->recurrenceDetail = $scheduleRecurrenceDetail;
        $this->recurrenceRange = $scheduleRecurrenceRange;
        $this->displayOrder = $scheduleOrder;
        $this->isPriority = $scheduleIsPriority;
        $response = $this->doPost('/schedule', $this->toArray());
       
        return $this->hydrate($response);
    }

    /**
     * Create Command event
     * @param $scheduleType
     * @param $scheduleFrom
     * @param $scheduleCommandId
     * @param $scheduleDisplays
     * @param $scheduleRecurrenceType
     * @param $scheduleRecurrenceDetail
     * @param $scheduleRecurrenceRange
     * @param $scheduleOrder
     * @param $scheduleIsPriority
     * @return XiboSchedule
     */
    public function createEventCommand($scheduleFrom, $scheduleCommandId, $scheduleDisplays, $scheduleRecurrenceType, $scheduleRecurrenceDetail, $scheduleRecurrenceRange, $scheduleOrder, $scheduleIsPriority)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->eventTypeId = 2;
        $this->fromDt = $scheduleFrom;
        $this->commandId = $scheduleCommandId;
        $this->displayGroupIds = $scheduleDisplays;
        $this->recurrenceType = $scheduleRecurrenceType;
        $this->recurrenceDetail = $scheduleRecurrenceDetail;
        $this->recurrenceRange = $scheduleRecurrenceRange;
        $this->displayOrder = $scheduleOrder;
        $this->isPriority = $scheduleIsPriority;
        $response = $this->doPost('/schedule', $this->toArray());
       
        return $this->hydrate($response);
    }

    /**
     * Create Overlay event
     * @param $scheduleType
     * @param $scheduleFrom
     * @param $scheduleTo
     * @param $scheduleCampaignId
     * @param $scheduleDisplays
     * @param $scheduledayPartId
     * @param $scheduleRecurrenceType
     * @param $scheduleRecurrenceDetail
     * @param $scheduleRecurrenceRange
     * @param $scheduleOrder
     * @param $scheduleIsPriority
     * @return XiboSchedule
     */
    public function createEvetOverlay($scheduleFrom, $scheduleTo, $scheduleCampaignId, $scheduleDisplays, $scheduledayPartId, $scheduleRecurrenceType, $scheduleRecurrenceDetail, $scheduleRecurrenceRange, $scheduleOrder, $scheduleIsPriority)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->eventTypeId = 3;
        $this->fromDt = $scheduleFrom;
        $this->toDt = $scheduleTo;
        $this->campaignId = $scheduleCampaignId;
        $this->displayGroupIds = $scheduleDisplays;
        $this->dayPartId = $scheduledayPartId;
        $this->recurrenceType = $scheduleRecurrenceType;
        $this->recurrenceDetail = $scheduleRecurrenceDetail;
        $this->recurrenceRange = $scheduleRecurrenceRange;
        $this->displayOrder = $scheduleOrder;
        $this->isPriority = $scheduleIsPriority;
        $response = $this->doPost('/schedule', $this->toArray());
       
        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $scheduleType
     * @param $scheduleFrom
     * @param $scheduleTo
     * @param $scheduleCampaignId
     * @param $scheduleCommandId
     * @param $scheduleDisplays
     * @param $scheduledayPartId
     * @param $scheduleRecurrenceType
     * @param $scheduleRecurrenceDetail
     * @param $scheduleRecurrenceRange
     * @param $scheduleOrder
     * @param $scheduleIsPriority
     * @return XiboSchedule
     */
    public function edit($scheduleType, $scheduleFrom, $scheduleTo, $scheduleCampaignId,$scheduleCommandId, $scheduleDisplays, $scheduledayPartId, $scheduleRecurrenceType, $scheduleRecurrenceDetail, $scheduleRecurrenceRange, $scheduleOrder, $scheduleIsPriority)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->eventTypeId = $scheduleType;
        $this->fromDt = $scheduleFrom;
        $this->toDt = $scheduleTo;
        $this->campaignId = $scheduleCampaignId;
        $this->commandId = $scheduleCommandId;
        $this->displayGroupIds = $scheduleDisplays;
        $this->dayPartId = $scheduledayPartId;
        $this->recurrenceType = $scheduleRecurrenceType;
        $this->recurrenceDetail = $scheduleRecurrenceDetail;
        $this->recurrenceRange = $scheduleRecurrenceRange;
        $this->displayOrder = $scheduleOrder;
        $this->isPriority = $scheduleIsPriority;
        $response = $this->doPut('/schedule/' . $this->eventId, $this->toArray());
       
        return $this->hydrate($response);
    }


    /**
     * Delete
     * @return bool
     */
    public function delete()
    {
        $this->doDelete('/schedule/' . $this->eventId);
        return true;
    }

}
