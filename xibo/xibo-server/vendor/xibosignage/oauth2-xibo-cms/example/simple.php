<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2015 Spring Signage Ltd
 * (simple.php)
 */
require '../vendor/autoload.php';

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create a provider
$provider = new \Xibo\OAuth2\Client\Provider\Xibo([
    'clientId' => 'UrFJuLgLAUempvleHp76IfRp1cKyNZcKELowDzzf',    // The client ID assigned to you by the provider
    'clientSecret' => 'YeZBaNUyOcN7gFwQ90oZ86Om3bsbvo8279ZYaG5HluYKXpe3xI9wUvcMuVhex3UljQufpLcAke8g7I9Bo30f0S1OG6oMcWeN0TGUd8OdagxDj2JTeukUhKlBTFVfVbwczuk8M7481d1wb8bQSaPPgRUUcefxOmvWyrKVhLmMvFQN3Oeo6TOyCDW0NX4kSEwLWYxZlVv78Byv8rid0UzoM08TvXRTNYshZI1z2U3M9gLnAWqFSpLAHHdCS8rAT0',   // The client password assigned to you by the provider
    'redirectUri' => '',
    'baseUrl' => 'http://192.168.0.15'
]);

$entityProvider = new \Xibo\OAuth2\Client\Provider\XiboEntityProvider($provider);

//$displayGroup = (new \Xibo\OAuth2\Client\Entity\XiboDisplayGroup($entityProvider))->getById(20);

//echo 'Display Group ID ' . $displayGroup->displayGroupId;

// Try creating a new one
//$new = (new \Xibo\OAuth2\Client\Entity\XiboDisplayGroup($entityProvider))->getById(44)->delete();

//var_export($new);

// Try creating a campaign
//$new = (new \Xibo\OAuth2\Client\Entity\XiboCampaign($entityProvider))->create('test campaign');

//echo json_encode($new, JSON_PRETTY_PRINT);

//Try creating new resolution 
//$new = (new \Xibo\OAuth2\Client\Entity\XiboResolution($entityProvider))->create('test resolution', 2069, 1069);

//echo json_encode($new, JSON_PRETTY_PRINT);

//$res = (new \Xibo\OAuth2\Client\Entity\XiboResolution($entityProvider))->getById(9);
//echo 'Resolution ' . $res->resolutionId;

//create new display group
$newDG = (new \Xibo\OAuth2\Client\Entity\XiboDisplayGroup($entityProvider))->create('phpunit test group', 'Api', 0, 0, '', null);
$newDG2 = (new \Xibo\OAuth2\Client\Entity\XiboDisplayGroup($entityProvider))->create('phpunit test group 2', 'Api 2', 0, 0, '', null);
//var_export($new);

//$new = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->create('test layout', 'test description', '', 9);

//echo json_encode($new, JSON_PRETTY_PRINT);

//$new = (new \Xibo\OAuth2\Client\Entity\XiboDisplayProfile($entityProvider))->create('test profile', 'android', 0);

//echo json_encode($new, JSON_PRETTY_PRINT);

//$newLayout = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->create('test layout', 'test description', '', 9);
//$newLayout2 = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->create('test layout2', 'test description', '', 9);

//$newRegion = (new \Xibo\OAuth2\Client\Entity\XiboRegion($entityProvider))->create($newLayout->layoutId,200,300,75,125);

//$region = $newRegion->regionId;

//echo json_encode($region, JSON_PRETTY_PRINT);


//$dataSetNew = (new \Xibo\OAuth2\Client\Entity\XiboDataSet($entityProvider))->create('dataset name', 'dataset desc');
//$columnNew = (new \Xibo\OAuth2\Client\Entity\XiboDataSetColumn($entityProvider))->create($dataSetNew->dataSetId, 'column name','', 2, 1, 1, '');
//$column = $columnNew->dataSetColumnId;
//$rowNew = (new \Xibo\OAuth2\Client\Entity\XiboDataSetRow($entityProvider))->create($dataSetNew->dataSetId, $columnNew->dataSetColumnId,'Cabbage');
//$row = $rowNew->rowId;
//echo json_encode($row, JSON_PRETTY_PRINT);

//$assign = $newDG->assignLayout($newLayout->layoutId);
$assign= $newDG->assignDisplaygroup($newDG2->displayGroupId);