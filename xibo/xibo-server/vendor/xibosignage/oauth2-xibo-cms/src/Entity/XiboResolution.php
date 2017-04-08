<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboResolution.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboResolution extends XiboEntity
{
    public $url = '/resolution';

    public $resolutionId;
    public $resolution;
    public $width;
    public $height;
    public $designerWidth;
    public $designerHeight;
    public $version = 2;
    public $enabled = 1;

    /**
     * @param array $params
     * @return array|XiboResolution
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
     * @return XiboResolution
     * @throws XiboApiException
     */
    public function getById($id)
    {
        $response = $this->doGet($this->url, [
            'resolutionId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return clone $this->hydrate($response[0]);
    }

    /**
     * Create
     * @param $resolutionName
     * @param $resolutionWidth
     * @param $resolutionHeight
     */
    public function create($resolutionName, $resolutionWidth, $resolutionHeight)
    {
    $this->userId = $this->getEntityProvider()->getMe()->getId();
    $this->resolution = $resolutionName;
    $this->width = $resolutionWidth;
    $this->height = $resolutionHeight;
    $response = $this->doPost('/resolution', $this->toArray());
   
    return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $resolutionName
     * @param $resolutionWidth
     * @param $resolutionHeight
     * @return XiboResolution
     */
    public function edit($resolutionName, $resolutionWidth, $resolutionHeight)
    {
    $this->resolution = $resolutionName;
    $this->width = $resolutionWidth;
    $this->height = $resolutionHeight;
    $response = $this->doPut('/resolution/' . $this->resolutionId, $this->toArray());
    return $this->hydrate($response);
    }

    /**
     * Delete
     * @return bool
     */
    public function delete()
    {
    $this->doDelete('/resolution/' . $this->resolutionId);
    return true;
    }
}
