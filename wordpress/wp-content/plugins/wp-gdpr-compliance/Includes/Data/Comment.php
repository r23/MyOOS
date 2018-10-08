<?php

namespace WPGDPRC\Includes\Data;

/**
 * Class Comment
 * @package WPGDPRC\Includes\Data
 */
class Comment {
    /** @var null */
    private static $instance = null;
    /** @var int */
    protected $id = 0;
    /** @var int */
    protected $postId = 0;
    /** @var string */
    protected $name = '';
    /** @var string */
    protected $emailAddress = '';
    /** @var string */
    protected $content = '';
    /** @var string */
    protected $ipAddress = '';
    /** @var string */
    protected $date = '';

    /**
     * Comment constructor.
     * @param int $id
     */
    public function __construct($id = 0) {
        if ((int)$id > 0) {
            $this->setId($id);
            $this->load();
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

    /**
     * @param \stdClass $row
     */
    public function loadByRow(\stdClass $row) {
        $this->setId($row->comment_ID);
        $this->setPostId($row->comment_post_ID);
        $this->setName($row->comment_author);
        $this->setEmailAddress($row->comment_author_email);
        $this->setIpAddress($row->comment_author_IP);
        $this->setContent($row->comment_content);
        $this->setDate($row->comment_date);
    }

    /**
     * @return null|Comment
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
    public function getPostId() {
        return $this->postId;
    }

    /**
     * @param int $postId
     */
    public function setPostId($postId) {
        $this->postId = $postId;
    }

    /**
     * @return string
     */
    public function getAuthorName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
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
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate($date) {
        $this->date = $date;
    }
}