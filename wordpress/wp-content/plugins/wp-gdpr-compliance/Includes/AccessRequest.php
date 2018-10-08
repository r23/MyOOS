<?php

namespace WPGDPRC\Includes;

/**
 * Class Requests
 * @package WPGDPRC\Includes
 */
class AccessRequest {
    /** @var null */
    private static $instance = null;
    /** @var int */
    private $id = 0;
    /** @var int */
    private $siteId = 0;
    /** @var string */
    private $emailAddress = '';
    /** @var string */
    private $sessionId = '';
    /** @var string */
    private $ipAddress = '';
    /** @var int */
    private $expired = 0;
    /** @var string */
    private $dateCreated = '';

    /**
     * AccessRequest constructor.
     * @param int $id
     */
    public function __construct($id = 0) {
        if ((int)$id > 0) {
            $this->setId($id);
            $this->load();
        }
    }

    /**
     * @param string $emailAddress
     * @param string $sessionId
     * @return bool|AccessRequest
     */
    public function getByEmailAddressAndSessionId($emailAddress = '', $sessionId = '') {
        global $wpdb;
        $query  = "SELECT * FROM `" . self::getDatabaseTableName() . "`";
        $query .= " WHERE `email_address` = '%s'";
        $query .= " AND `session_id` = '%s'";
        $query .= " AND `expired` = '0'";
        $query .= " AND `site_id` = '%d'";
        $row = $wpdb->get_row($wpdb->prepare($query, $emailAddress, $sessionId, get_current_blog_id()));
        if ($row !== null) {
            return new self($row->ID);
        }
        return false;
    }

    /**
     * @param array $filters
     * @return int
     */
    public function getTotal($filters = array()) {
        global $wpdb;
        $query = "SELECT COUNT(`ID`) FROM `" . self::getDatabaseTableName() . "` WHERE 1";
        $query .= Helper::getQueryByFilters($filters);
        $query .= sprintf(" AND `site_id` = '%d'", get_current_blog_id());
        $result = $wpdb->get_var($query);
        if ($result !== null) {
            return absint($result);
        }
        return 0;
    }

    /**
     * @param array $filters
     * @param int $limit
     * @param int $offset
     * @return AccessRequest[]
     */
    public function getList($filters = array(), $limit = 0, $offset = 0) {
        global $wpdb;
        $output = array();
        $query  = "SELECT * FROM `" . self::getDatabaseTableName() . "` WHERE 1";
        $query .= Helper::getQueryByFilters($filters);
        $query .= sprintf(" AND `site_id` = '%d'", get_current_blog_id());
        $query .= " ORDER BY `date_created` DESC";
        if (!empty($limit)) {
            $query .= " LIMIT $offset, $limit";
        }
        $results = $wpdb->get_results($query);
        if ($results !== null) {
            foreach ($results as $row) {
                $object = new self;
                $object->loadByRow($row);
                $output[] = $object;
            }
        }
        return $output;
    }

    /**
     * @param $row
     */
    private function loadByRow($row) {
        $this->setId($row->ID);
        $this->setSiteId($row->site_id);
        $this->setEmailAddress($row->email_address);
        $this->setSessionId($row->session_id);
        $this->setIpAddress($row->ip_address);
        $this->setExpired($row->expired);
        $this->setDateCreated($row->date_created);
    }

    public function load() {
        global $wpdb;
        $query = "SELECT * FROM `" . self::getDatabaseTableName() . "` WHERE `ID` = '%d'";
        $row = $wpdb->get_row($wpdb->prepare($query, $this->getId()));
        if ($row !== null) {
            $this->loadByRow($row);
        }
    }

    /**
     * @param int $id
     * @return bool
     */
    public function exists($id = 0) {
        global $wpdb;
        $row = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM `" . self::getDatabaseTableName() . "` WHERE `ID` = '%d'",
                intval($id)
            )
        );
        return ($row !== null);
    }

    /**
     * @param string $emailAddress
     * @param bool $nonExpiredOnly
     * @return bool
     */
    public function existsByEmailAddress($emailAddress = '', $nonExpiredOnly = false) {
        global $wpdb;
        $query  = "SELECT * FROM `" . self::getDatabaseTableName() . "`";
        $query .= " WHERE `email_address` = '%s'";
        $query .= " AND `site_id` = '%d'";
        if ($nonExpiredOnly) {
            $query .= " AND `expired` = '0'";
        }
        $row = $wpdb->get_row($wpdb->prepare($query, $emailAddress, get_current_blog_id()));
        return ($row !== null);
    }

    /**
     * @return bool|int
     */
    public function save() {
        global $wpdb;
        if ($this->exists($this->getId())) {
            $wpdb->update(
                self::getDatabaseTableName(),
                array('expired' => $this->getExpired()),
                array('ID' => $this->getId()),
                array('%d'),
                array('%d')
            );
            return $this->getId();
        } else {
            $result = $wpdb->insert(
                self::getDatabaseTableName(),
                array(
                    'site_id' => $this->getSiteId(),
                    'email_address' => $this->getEmailAddress(),
                    'session_id' => $this->getSessionId(),
                    'ip_address' => $this->getIpAddress(),
                    'expired' => $this->getExpired(),
                    'date_created' => date_i18n('Y-m-d H:i:s'),
                ),
                array('%d', '%s', '%s', '%s', '%d', '%s')
            );
            if ($result !== false) {
                $this->setId($wpdb->insert_id);
                return $this->getId();
            }
        }
        return false;
    }

    /**
     * @return null|AccessRequest
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
     * @return int
     */
    public function getSiteId() {
        return $this->siteId;
    }

    /**
     * @param int $siteId
     */
    public function setSiteId($siteId) {
        $this->siteId = $siteId;
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
    public function getSessionId() {
        return $this->sessionId;
    }

    /**
     * @param string $sessionId
     */
    public function setSessionId($sessionId) {
        $this->sessionId = $sessionId;
    }

    /**
     * @return string
     */
    public function getIpAddress() {
        return $this->ipAddress;
    }

    /**
     * @param string $ipAddress
     */
    public function setIpAddress($ipAddress) {
        $this->ipAddress = $ipAddress;
    }

    /**
     * @return int
     */
    public function getExpired() {
        return $this->expired;
    }

    /**
     * @param int $expired
     */
    public function setExpired($expired) {
        $this->expired = $expired;
    }

    /**
     * @return string
     */
    public function getDateCreated() {
        return $this->dateCreated;
    }

    /**
     * @param string $dateCreated
     */
    public function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return bool
     */
    public static function databaseTableExists() {
        global $wpdb;
        $result = $wpdb->query("SHOW TABLES LIKE '" . self::getDatabaseTableName() . "'");
        return ($result === 1);
    }

    /**
     * @return string
     */
    public static function getDatabaseTableName() {
        global $wpdb;
        return $wpdb->base_prefix . 'wpgdprc_access_requests';
    }
}