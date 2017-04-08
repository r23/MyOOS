<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboStats.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboStats extends XiboEntity
{
	private $url = '/stats';

	public $type;
	public $display;
	public $layout;
	public $media;
	public $numberPlays;
	public $duration;
	public $minStart;
	public $maxEnd;

	/**
	 * @param array $params
	 * @return array|XiboStats
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
}
