<?php

namespace WPGDPRC\Includes;

/**
 * Class DeleteRequest
 * @package WPGDPRC\Includes
 */
class DeleteRequest {
    /** @var null */
    private static $instance = null;
    /** @var int */
    private $id = 0;
    /** @var int */
    private $siteId = 0;
    /** @var int */
    private $accessRequestId = 0;
    /** @var string */
    private $sessionId = '';
    /** @var string */
    private $ipAddress = '';
    /** @var int */
    private $dataId = 0;
    /** @var string */
    private $type = '';
    /** @var int */
    private $processed = 0;
    /** @var string */
    private $dateCreated = '';

    /**
     * DeleteRequest constructor.
     * @param int $id
     */
    public function __construct($id = 0) {
        if ((int)$id > 0) {
            $this->setId($id);
            $this->load();
        }
    }

    /**
     * @param string $type
     * @param int $dataId
     * @param int $accessRequestId
     * @return bool|DeleteRequest
     */
    public function getByTypeAndDataIdAndAccessRequestId($type = '', $dataId = 0, $accessRequestId = 0) {
        global $wpdb;
        $query = "SELECT `ID` FROM `" . self::getDatabaseTableName() . "`";
        $query .= " WHERE `type` = '%s'";
        $query .= " AND `data_id` = '%d'";
        $query .= " AND `access_request_id` = '%d'";
        $query .= " AND `site_id` = '%d'";
        $result = $wpdb->get_row($wpdb->prepare($query, $type, $dataId, $accessRequestId, get_current_blog_id()));
        if ($result !== null) {
            return new self($result->ID);
        }
        return false;
    }

    /**
     * @param int $accessRequestId
     * @return int
     */
    public function getAmountByAccessRequestId($accessRequestId = 0) {
        global $wpdb;
        $query = "SELECT COUNT(`ID`) FROM `" . self::getDatabaseTableName() . "`";
        $query .= " WHERE `access_request_id` = '%d'";
        $query .= " AND `processed` = '0'";
        $query .= " AND `site_id` = '%d'";
        $result = $wpdb->get_var($wpdb->prepare($query, intval($accessRequestId), get_current_blog_id()));
        if ($result !== null) {
            return absint($result);
        }
        return 0;
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
     * @return DeleteRequest[]
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
        $this->setAccessRequestId($row->access_request_id);
        $this->setSessionId($row->session_id);
        $this->setIpAddress($row->ip_address);
        $this->setDataId($row->data_id);
        $this->setType($row->type);
        $this->setProcessed($row->processed);
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
     * @return bool|int
     */
    public function save() {
        global $wpdb;
        if ($this->exists($this->getId())) {
            $wpdb->update(
                self::getDatabaseTableName(),
                array('processed' => $this->getProcessed()),
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
                    'access_request_id' => $this->getAccessRequestId(),
                    'session_id' => $this->getSessionId(),
                    'ip_address' => $this->getIpAddress(),
                    'type' => $this->getType(),
                    'data_id' => $this->getDataId(),
                    'processed' => $this->getProcessed(),
                    'date_created' => date_i18n('Y-m-d H:i:s'),
                ),
                array('%d', '%d', '%s', '%s', '%s', '%d', '%d', '%s')
            );
            if ($result !== false) {
                $this->setId($wpdb->insert_id);
                return $this->getId();
            }
        }
        return false;
    }

    /**
     * @return null|string
     */
    public function getManageUrl() {
        switch ($this->getType()) {
            case 'user' :
                return get_edit_user_link($this->getDataId());
                break;
            case 'comment' :
                return get_edit_comment_link($this->getDataId());
                break;
            case 'woocommerce_order' :
                return get_edit_post_link($this->getDataId());
                break;
        }
        return '';
    }

    /**
     * @return string
     */
    public function getNiceTypeLabel() {
        switch ($this->getType()) {
            case 'user' :
                $output = __('User', WP_GDPR_C_SLUG);
                break;
            case 'comment' :
                $output = __('Comment', WP_GDPR_C_SLUG);
                break;
            case 'woocommerce_order' :
                $output = __('WooCommerce Order', WP_GDPR_C_SLUG);
                break;
            default :
                $output = $this->getType();
                break;
        }
        return $output;
    }

    /**
     * @return null|DeleteRequest
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
     * @return int
     */
    public function getAccessRequestId() {
        return $this->accessRequestId;
    }

    /**
     * @param int $accessRequestId
     */
    public function setAccessRequestId($accessRequestId) {
        $this->accessRequestId = $accessRequestId;
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
    public function getDataId() {
        return $this->dataId;
    }

    /**
     * @param int $dataId
     */
    public function setDataId($dataId) {
        $this->dataId = $dataId;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getProcessed() {
        return $this->processed;
    }

    /**
     * @param int $processed
     */
    public function setProcessed($processed) {
        $this->processed = $processed;
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
        return $wpdb->base_prefix . 'wpgdprc_delete_requests';
    }
}