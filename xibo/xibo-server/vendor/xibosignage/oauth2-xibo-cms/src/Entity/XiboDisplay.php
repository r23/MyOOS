<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboDisplay.php)
 */
namespace Xibo\OAuth2\Client\Entity;
use Xibo\OAuth2\Client\Exception\XiboApiException;
/**
 * Class XiboDisplay
 * @package Xibo\OAuth2\Client\Entity
 */
class XiboDisplay extends XiboEntity
{
    public $displayId;
    public $display;
    public $description;
    public $isAuditing = 0;
    public $defaultLayoutId = 0;
    public $license;
    public $licensed;
    public $loggedIn;
    public $lastAccessed;
    public $incSchedule;
    public $emailAlert;
    public $alertTimeout;
    public $clientAddress;
    public $mediaInventoryStatus;
    public $macAddress;
    public $lastChanged;
    public $numberOfMacAddressChanges;
    public $lastWakeOnLanCommandSent;
    public $wakeOnLanEnabled;
    public $wakeOnLanTime;
    public $broadCastAddress;
    public $secureOn;
    public $cidr;
    public $latitude;
    public $longitude;
    public $versionInstructions;
    public $clientType;
    public $clientVersion;
    public $clientCode;
    public $displayProfileId;
    public $currentLayoutId;
    public $screenShotRequested;
    public $storageAvailableSpace;
    public $storageTotalSpace;
    public $displayGroupId;
    public $currentLayout;
    public $defaultLayout;
    public $xmrChannel;
    public $xmrPubKey;
    public $lastCommandSuccess;
    public $displayGroups = [];
    /**
     * @param array $params
     * @return array[XiboDisplay]
     */
    public function get(array $params = [])
    {
        $entries = [];
        $response = $this->doGet('/display', $params);
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
        $response = $this->doGet('/display', [
            'displayGroupId' => $id
        ]);
        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single display, found ' . count($response));
        return clone $this->hydrate($response[0]);
    }
    /**
     * Edit
     * @param $displayName;
     * @param $displayDescription;
     * @param $displayIsAuditing;
     * @param $displayDefaultLayoutId
     * @param $displaLicensed;
     * @param $displayHardwareKey;
     * @param $displayIncSchedule;
     * @param $displayEmailAlert;
     * @param $displayWoL
     * @param $displayBroadcast
     * @return XiboLayout
     */
    public function edit($displayName, $displayDescription, $displayIsAuditing, $displayDefaultLayoutId, $displayLicensed, $displayHardwareKey, $displayIncSchedule, $displayEmailAlert, $displayWoL, $displayBroadcast)
    {
        $this->display = $displayName;
        $this->description = $displayDescription;
        $this->isAuditing = $displayIsAuditing;
        $this->defaultLayoutId = $displayDefaultLayoutId;
        $this->licensed = $displayLicensed;
        $this->license = $displayHardwareKey;
        $this->incSchedule = $displayIncSchedule;
        $this->emailAlert = $displayEmailAlert;
        $this->wakeOnLanTime = $displayWoL;
        $this->broadCastAddress = $displayBroadcast;
        $response = $this->doPut('/display/' . $this->displayId, $this->toArray());
        
        return $this->hydrate($response);
    }
    /**
      * Delete
      * @return bool
      */
    public function delete()
    {
        $this->doDelete('/display/' . $this->displayId);
        
        return true;
    }
}