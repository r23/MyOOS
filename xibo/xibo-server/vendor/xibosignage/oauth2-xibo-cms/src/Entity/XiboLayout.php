<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboLayout.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboLayout extends XiboEntity
{
    private $url = '/layout';

    public $layoutId;
    public $ownerId;
    public $campaignId;
    public $backgroundImageId;
    public $schemaVersion;
    public $layout;
    public $description;
    public $backgroundColor;
    public $createdDt;
    public $modifiedDt;
    public $status;
    public $retired;
    public $backgroundzIndex;
    public $width;
    public $height;
    public $displayOrder;
    public $duration;
    public $tags;

    /**
     * @param array $params
     * @return array|XiboLayout
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
     * @return XiboLayout
     * @throws XiboApiException
     */
    public function getById($id)
    {
        $response = $this->doGet($this->url, [
            'layoutId' => $id, 'retired' => -1
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return clone $this->hydrate($response[0]);
    }

    /**
     * Create
     * @param $layoutName
     * @param $layoutDescription
     * @param $layoutTemplateId
     * @param $layoutResolutionId
     */
    public function create($layoutName, $layoutDescription, $layoutTemplateId, $layoutResolutionId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $layoutName;
        $this->description = $layoutDescription;
        $this->layoutId = $layoutTemplateId;
        $this->resolutionId = $layoutResolutionId;
        $response = $this->doPost('/layout', $this->toArray());
        
        $layout = $this->hydrate($response);
        
        foreach ($response['regions'] as $item) {
            $region = new XiboRegion($this->getEntityProvider());
            $region->hydrate($item);
            // Add to parent object
            $layout->regions[] = $region;
        }
        
        return $layout;
    }

    /**
     * Edit
     * @param $layoutName;
     * @param $layoutDescription;
     * @param $layoutTags;
     * @param $layoutRetired;
     * @param $layoutBackgroundC;
     * @param $layoutBackgroundImg;
     * @param $layoutBackgroundzIndex;
     * @param $layoutResolutionId;
     * @return XiboLayout
     */
    public function edit($layoutName, $layoutDescription, $layoutTags, $layoutRetired, $layoutBackgroundC,$layoutBackgroundImg, $layoutBackgroundzIndex, $layoutResolutionId)
    {
        $this->name = $layoutName;
        $this->description = $layoutDescription;
        $this->tags = $layoutTags;
        $this->retired = $layoutRetired;
        $this->backgroundColor = $layoutBackgroundC;
        $this->backgroundImageId = $layoutBackgroundImg;
        $this->backgroundzIndex = $layoutBackgroundzIndex;
        $this->resolutionId = $layoutResolutionId;
        $response = $this->doPut('/layout/' . $this->layoutId, $this->toArray());
        
        return $this->hydrate($response);
    }


    /**
     * Delete
     * @return bool
     */
    public function delete()
    {
        $this->doDelete('/layout/' . $this->layoutId);
        
        return true;
    }


    /**
     * Copy
     * @param $layoutName
     * @param $layoutDescription
     * @param $layoutCopyFiles
     */
    public function copy($layoutName, $layoutDescription, $layoutCopyFiles)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $layoutName;
        $this->description = $layoutDescription;
        $this->copyMediaFiles = $layoutCopyFiles;
        $response = $this->doPost('/layout/copy/' . $this->layoutId, $this->toArray());
        
        return $this->hydrate($response);
    }


    /**
     * Create Region
     */

    public function createRegion($regionWidth, $regionHeight, $regionTop, $regionLeft)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->width = $regionWidth;
        $this->height = $regionHeight;
        $this->top = $regionTop;
        $this->left = $regionLeft; 

        $response = $this->doPost('/region/' . $this->layoutId, $this->toArray());
        
        return $this->hydrate($response);
    }

    /**
     * Edit Region
     */

    public function editRegion($regionWidth, $regionHeight, $regionTop, $regionLeft, $regionzIndex, $regionLoop)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->width = $regionWidth;
        $this->height = $regionHeight;
        $this->top = $regionTop;
        $this->left = $regionLeft; 
        $this->zIndex = $regionzIndex;
        $this->loop = $regionLoop;

        $response = $this->doPut('/region/' . $this->regionId, $this->toArray());
        
        return $this->hydrate($response);
    }

    /**
     * Delete Region
     * @return bool
     */
    public function deleteRegion()
    {
        $this->doDelete('/region/' . $this->regionId);
        
        return true;
    }

    /**
     * @param $id
     * @return XiboLayout
     * @throws XiboApiException
     */
    public function getByTemplateId($id)
    {
        $response = $this->doGet('/template', [
            'templateId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return $this->hydrate($response[0]);
    }

    /**
     * Add tag
     * @param $layoutTags
     * @return XiboLayout
     */
    public function addTag($layoutTags)
    {
        $this->tag = $layoutTags;
        $response = $this->doPost('/layout/' . $this->layoutId . '/tag', [
            'tag' => [$layoutTags]
            ]);

        return $this;
    }
}
