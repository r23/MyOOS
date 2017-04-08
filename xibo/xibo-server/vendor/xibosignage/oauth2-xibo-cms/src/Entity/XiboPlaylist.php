<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboPlaylist.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

/**
 * Class XiboPlaylist
 * @package Xibo\OAuth2\Client\Entity
 */
class XiboPlaylist extends XiboEntity
{
    public $playlistId;
    public $ownerId;
    public $name;
    public $tags;
    public $regions;
    public $widgets;
    public $permissions;
    public $displayOrder;
    public $duration;
	
	/**
     * Assign media to playlist
     * @param $playlistMedia
     * @param $playlistRegion
     * @return XiboPlaylist
     */
    public function assign($playlistMedia, $duration, $playlistRegion)
    {
        $this->playlistId = $playlistRegion;
        $this->media = $playlistMedia;
        $response = $this->doPost('/playlist/library/assign/' . $playlistRegion, [
        	'media' => $playlistMedia,
            'duration' => $duration
        	]);

        // Parse response
        //  set properties
        $this->playlistId = $response['playlistId'];
        
        // Set widgets property (with XiboWidget objects)
        foreach ($response['widgets'] as $widget) {
            $this->widgets[] = (new XiboWidget($this->getEntityProvider()))->hydrate($widget);
        }

        return $this;
    }
}