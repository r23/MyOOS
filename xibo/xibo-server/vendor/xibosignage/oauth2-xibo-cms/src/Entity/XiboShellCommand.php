<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboShellCommand.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboShellCommand extends XiboEntity
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
    public $windowsCommand;
    public $linuxCommand;
    public $launchThroughCmd;
    public $terminateCommand;
    public $commandCode;

    /**
     * Get by Id
     * @param $id
     * @return $this|XiboShellCommand
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
     * @param $duration
     * @param $useDuration
     * @param $windowsCommand
     * @param $linuxCommand
     * @param $launchThroughCmd
     * @param $terminateCommand
     * @param $useTaskKill
     * @param $commandCode
     */
    public function create($name, $duration, $useDuration, $windowsCommand, $linuxCommand, $launchThroughCmd, $terminateCommand, $useTaskkill, $commandCode, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->windowsCommand = $windowsCommand;
        $this->linuxCommand = $linuxCommand;
        $this->launchThroughCmd = $launchThroughCmd;
        $this->terminateCommand = $terminateCommand;
        $this->useTaskkill = $useTaskkill;
        $this->commandCode = $commandCode;
        $this->playlistId = $playlistId;
        $response = $this->doPost('/playlist/widget/shellCommand/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $name
     * @param $duration
     * @param $useDuration
     * @param $windowsCommand
     * @param $linuxCommand
     * @param $launchThroughCmd
     * @param $terminateCommand
     * @param $useTaskKill
     * @param $commandCode
     */
    public function edit($name, $duration, $useDuration, $windowsCommand, $linuxCommand, $launchThroughCmd, $terminateCommand, $useTaskkill, $commandCode, $widgetId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->windowsCommand = $windowsCommand;
        $this->linuxCommand = $linuxCommand;
        $this->launchThroughCmd = $launchThroughCmd;
        $this->terminateCommand = $terminateCommand;
        $this->useTaskkill = $useTaskkill;
        $this->commandCode = $commandCode;
        $this->widgetId = $widgetId;
        $response = $this->doPut('/playlist/widget/' . $widgetId , $this->toArray());

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
