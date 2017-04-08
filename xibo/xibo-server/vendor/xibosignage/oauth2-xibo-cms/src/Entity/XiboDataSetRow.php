<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboDataSetRow.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboDataSetRow extends XiboEntity
{

	private $url = '/dataset';

    public $dataSetId;
    public $dataSetColumnId;
    public $rowId;
    public $rowDataSetId;
    public $dataSet;
    public $description;
    public $userId;
    public $lastDataEdit;
    public $owner;
    public $groupsWithPermissions;
    public $code;
    public $isLookup;
    public $heading;
    public $listContent;
    public $columnOrder;
    public $dataTypeId;
    public $dataSetColumnTypeId;
    public $formula;
    public $dataType;
    public $dataSetColumnType;
    public $dataSetColumnId_ID;

    /**
     * @param $id
     * @return XiboDataSetRow
     * @throws XiboApiException
     * @return XiboDataSetRow
     */
    public function getById($rowDataSetId, $id)
    {
        $this->dataSetId = $rowDataSetId;
        $response = $this->doGet('/dataset/data/'. $this->dataSetId, [
            'rowId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return $response[0];
    }

    /**
     * Create Row
     * @param $rowData
     * @param $rowDataSetId
     * @param $columnId
     * @return XiboDataSetRow
     */
    public function create($rowDataSetId, $columnId, $rowData)
    {
        $this->dataSetId = $rowDataSetId;
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $response = $this->doPost('/dataset/data/'. $this->dataSetId, [
            'dataSetColumnId_' . $columnId => $rowData
            ]);
        
        return $response;
    }

    /**
     * Edit Row
     * @param $rowData
     * @param $rowDataSetId
     * @param $columnId
     * @return XiboDataSetRow
     */
    public function edit($rowDataSetId, $columnId, $rowData)
    {
        $this->dataSetId = $rowDataSetId;
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $response = $this->doPut('/dataset/data/'. $this->dataSetId . $this->rowId, [
            'dataSetColumnId_' . $columnId => $rowData
            ]);
        
        return $response;
    }

    /**
     * Delete Row
     * @return bool
     */
    public function delete()
    {
        $this->doDelete('/dataset/data/' . $this->dataSetId . $this->rowId);
        
        return true;
    }
}
