<?php

namespace WPGDPRC\Includes\Data;

/**
 * Class User
 * @package WPGDPRC\Includes\Data
 */
class User {
    /** @var null */
    private static $instance = null;
    /** @var int */
    protected $id = 0;
    /** @var string */
    protected $username = '';
    /** @var string */
    protected $displayName = '';
    /** @var string */
    protected $emailAddress = '';
    /** @var string */
    protected $website = '';
    /** @var array */
    protected $metaData = array();
    /** @var string */
    protected $registeredDate = '';

    /**
     * User constructor.
     * @param int $id
     */
    public function __construct($id = 0) {
        if ((int)$id > 0) {
            $this->setId($id);
            $this->load();
            $this->loadMetaData();
        }
    }

    public function load() {
        global $wpdb;
        $query = "SELECT * FROM `" . $wpdb->users . "` WHERE `ID` = '%d'";
        $row = $wpdb->get_row($wpdb->prepare($query, $this->getId()));
        if ($row !== null) {
            $this->loadByRow($row);
        }
    }

    public function loadMetaData() {
        $this->setMetaData($this->getMetaDataByUserId($this->getId()));
    }

    /**
     * @param \stdClass $row
     */
    public function loadByRow(\stdClass $row) {
        $this->setId($row->ID);
        $this->setUsername($row->user_login);
        $this->setDisplayName($row->display_name);
        $this->setEmailAddress($row->user_email);
        $this->setWebsite($row->user_url);
        $this->setRegisteredDate($row->user_registered);
    }

    /**
     * @param int $userId
     * @return array
     */
    public function getMetaDataByUserId($userId = 0) {
        global $wpdb;
        $output = array();
        $query = "SELECT * FROM `" . $wpdb->usermeta . "` WHERE `user_id` = '%d'";
        $results = $wpdb->get_results($wpdb->prepare($query, $userId));
        if ($results !== null) {
            foreach ($results as $row) {
                $output[] = $row;
            }
        }
        return $output;
    }

    /**
     * @return null|User
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getDisplayName() {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName($displayName) {
        $this->displayName = $displayName;
    }

    /**
     * @return string
     */
    public function getEmailAddress() {
        return $this->emailAddress;
    }

    /**
     * @param string $emailAddress
     */
    public function setEmailAddress($emailAddress) {
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return string
     */
    public function getWebsite() {
        return $this->website;
    }

    /**
     * @param string $website
     */
    public function setWebsite($website) {
        $this->website = $website;
    }

    /**
     * @return array
     */
    public function getMetaData() {
        return $this->metaData;
    }

    /**
     * @param array $metaData
     */
    public function setMetaData($metaData) {
        $this->metaData = $metaData;
    }

    /**
     * @return string
     */
    public function getRegisteredDate() {
        return $this->registeredDate;
    }

    /**
     * @param string $registeredDate
     */
    public function setRegisteredDate($registeredDate) {
        $this->registeredDate = $registeredDate;
    }
}