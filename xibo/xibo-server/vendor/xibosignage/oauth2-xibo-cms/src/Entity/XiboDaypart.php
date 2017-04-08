<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboDaypart.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboDaypart extends XiboEntity
{
	private $url = '/daypart';
    
	public $dayPartId;
	public $name;
	public $description;
	public $startTime;
	public $endTime;
	public $exceptionDays;
	public $exceptionStartTimes;
	public $exceptionEndTimes;

	/**
     * @param array $params
     * @return array|XiboDaypart
     */
    public function get(array $params = [])
    {
        $entries = [];
        $response = $this->doGet($this->url, $params);

        foreach ($response as $item) {
            $entries[] = clone $this->hydrate($item);
        }

        return $entries;
    }

    /**
     * @param $id
     * @return XiboDaypart
     * @throws XiboApiException
     */
    public function getById($id)
    {
        $response = $this->doGet($this->url, [
            'dayPartId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return clone $this->hydrate($response[0]);
    }

    /**
     * Create
     * @param $name
     * @param $description
     * @param $startTime
     * @param $endTime
     * @param $exceptionDays
     * @param $exceptionStartTimes
     * @param $exceptionEndTimes
     * @return XiboDaypart
     */
    public function create($name, $description, $startTime, $endTime, $exceptionDays, $exceptionStartTimes, $exceptionEndTimes)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->description = $description;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->exceptionDays = $exceptionDays;
        $this->exceptionStartTimes = $exceptionStartTimes;
        $this->exceptionEndTimes = $exceptionEndTimes;

        $response = $this->doPost('/daypart', $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Delete
     * @return bool
     */
    public function delete()
    {
        $this->doDelete('/daypart/' . $this->dayPartId);
        
        return true;
    }

}