<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboCommand.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboCommand extends XiboEntity
{
	private $url = '/command';

	public $commandId;
	public $command;
	public $code;
	public $description;
	public $userId;
	public $commandString;
	public $validationString;

	/**
	 * @param array $params
	 * @return array|XiboCommand
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
	 * @return XiboCommand
	 * @throws XiboApiException
	 */
	public function getById($id)
	{
		$response = $this->doGet($this->url, [
			'commandId' => $id
		]);
		if (count($response) <= 0)
		throw new XiboApiException('Expecting a single record, found ' . count($response));

		return clone $this->hydrate($response[0]);
	}


	/**
	 * Create
	 * @param $commandName
	 * @param $commandDescription
	 * @param $commandCode
	 * @return XiboCommand
	 */
	public function create($commandName, $commandDescription, $commandCode)
	{
		$this->userId = $this->getEntityProvider()->getMe()->getId();
		$this->command = $commandName;
		$this->description = $commandDescription;
		$this->code = $commandCode;
		$response = $this->doPost('/command', $this->toArray());

		return $this->hydrate($response);
	}


	/**
	 * Edit
	 * @param $commandName
	 * @param $commandDescription
	 * @param $commandCode
	 * @return XiboCommand
	 */
	public function edit($commandName, $commandDescription, $commandCode)
	{
		$this->command = $commandName;
		$this->description = $commandDescription;
		$this->code = $commandCode;
		$response = $this->doPut('/command/' . $this->commandId, $this->toArray());
	
		return $this->hydrate($response);
	}

	/**
	 * Delete
	 * @return bool
	 */
	public function delete()
	{
		$this->doDelete('/command/' . $this->commandId);
	
		return true;
	}
}
