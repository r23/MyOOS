<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboDisplayGroup.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

/**
 * Class XiboDisplayGroup
 * @package Xibo\OAuth2\Client\Entity
 */
class XiboDisplayGroup extends XiboEntity
{
    public $displayGroupId;
    public $displayGroup;
    public $description;
    public $isDisplaySpecific = 0;
    public $isDynamic = 0;
    public $dynamicCriteria;
    public $userId = 0;

    /**
     * @param array $params
     * @return array[XiboDisplayGroup]
     */
    public function get(array $params = [])
    {
        $entries = [];
        $response = $this->doGet('/displaygroup', $params);

        foreach ($response as $item) {
            $entries[] = clone $this->hydrate($item);
        }

        return $entries;
    }

    /**
     * Get by Id
     * @param $id
     * @return $this|XiboDisplayGroup
     * @throws XiboApiException
     */
    public function getById($id)
    {
        $response = $this->doGet('/displaygroup', [
            'displayGroupId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single display group, found ' . count($response));

        return clone $this->hydrate($response[0]);
    }

    /**
     * Create
     * @param $groupName
     * @param $groupDescription
     * @param $isDynamic
     * @param $dynamicCriteria
     * @return XiboDisplayGroup
     */
    public function create($groupName, $groupDescription, $isDynamic, $dynamicCriteria)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->displayGroup = $groupName;
        $this->description = $groupDescription;
        $this->isDynamic = $isDynamic;
        $this->dynamicCriteria = $dynamicCriteria;

        $response = $this->doPost('/displaygroup', $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $groupName
     * @param $groupDescription
     * @param $isDynamic
     * @param $dynamicCriteria
     * @return XiboDisplayGroup
     */
    public function edit($groupName, $groupDescription, $isDynamic, $dynamicCriteria)
    {
        $this->displayGroup = $groupName;
        $this->description = $groupDescription;
        $this->isDynamic = $isDynamic;
        $this->dynamicCriteria = $dynamicCriteria;

        $response = $this->doPut('/displaygroup/' . $this->displayGroupId, $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Delete
     * @return bool
     */
    public function delete()
    {
        $this->doDelete('/displaygroup/' . $this->displayGroupId);

        return true;
    }
    
    /**
     * Assign display
     * @param $groupDisplay
     * @param int $displayGroupId
     * @return XiboDisplayGroup
     */
    public function assignDisplay($groupDisplay)
    {

        $response = $this->doPost('/displaygroup/' . $this->displayGroupId . '/display/assign', [
            'displayId' => $groupDisplay
            ]);

        return $this;
    }

    /**
     * Assign display group
     * @param $groupDisplayGroup
     * @param int $displayGroupId
     * @return XiboDisplayGroup
     */
    public function assignDisplayGroup($groupDisplayGroup)
    {

        $response = $this->doPost('/displaygroup/' . $this->displayGroupId . '/displayGroup/assign', [
        'displayGroupId' => $groupDisplayGroup
        ]);
        return $this;
    }

    /**
     * Assign layout
     * @param $groupLayout
     * @param int $displayGroupId
     * @return XiboDisplayGroup
     */
    public function assignLayout($groupLayout)
    {

        $response = $this->doPost('/displaygroup/' . $this->displayGroupId . '/layout/assign', [
            'layoutId' => $groupLayout
            ]);

        return $this;
    }

    /**
     * Assign media
     * @param $groupMedia
     * @param int $displayGroupId
     * @return XiboDisplayGroup
     */
    public function assignMedia($groupMedia)
    {

        $response = $this->doPost('/displaygroup/' . $this->displayGroupId . '/media/assign', [
            'mediaId' => $groupMedia
            ]);

        return $this;
    }
}
