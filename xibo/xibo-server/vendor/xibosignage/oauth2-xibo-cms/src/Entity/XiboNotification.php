<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboNotification.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;
class XiboNotification extends XiboEntity
{
	private $url = '/notification';
	public $notificationId;
    public $createdDt;
    public $releaseDt;
    public $subject;
    public $body;
    public $isEmail;
    public $isInterrupt;
    public $isSystem;
    public $userId;
    public $displayGroupId;

    /**
     * @param array $params
     * @return array[XiboNotification]
     */
    public function get(array $params = [])
    {
        $entries = [];
        $response = $this->doGet('/notification', $params);

        foreach ($response as $item) {
            $entries[] = clone $this->hydrate($item);
        }

        return $entries;
    }

    /**
     * Create
     * @param $subjectN
     * @param $bodyN
     * @param $releaseDtN
     * @param $isEmailN
     * @param $isInterruptN
     * @param $displaygroupN
     * @return XiboNotification
     */
    public function create($subjectN, $bodyN, $releaseDtN, $isEmailN, $isInterruptN, $displayGroupN)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->subject = $subjectN;
        $this->body = $bodyN;
        $this->releaseDt = $releaseDtN;
        $this->isEmail = $isEmailN;
        $this->isInterrupt = $isInterruptN;
        $this->displayGroupIds = $displayGroupN;

        $response = $this->doPost('/notification', $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Delete
     * @return bool
     */
    public function delete()
    {
        $this->doDelete('/notification/' . $this->notificationId);
        return true;
    }
}