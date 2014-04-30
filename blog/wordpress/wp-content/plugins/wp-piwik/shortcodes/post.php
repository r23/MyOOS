<?php
/*********************************
	WP-Piwik::Short:Post
**********************************/
if (!class_exists('WP_Piwik_MetaBox_PerPost_Stats'))
	$this->includeFile('classes/WP_Piwik_MetaBox_PerPost_Stats');
	
$perPostClass = new WP_Piwik_MetaBox_PerPost_Stats($this->subClassConfig());
$this->strResult = $perPostClass->getValue($this->aryAttributes['range'], $this->aryAttributes['key']); 