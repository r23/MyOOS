<?php

namespace WPGDPRC\Includes;

/**
 * Class Consent
 * @package WPGDPRC\Includes
 */
class Consent {
    /** @var null */
    private static $instance = null;
    /** @var int */
    private $id = 0;
    /** @var int */
    private $siteId = 0;
    /** @var string */
    private $title = '';
    /** @var string */
    private $description = '';
    /** @var string */
    private $snippet = '';
    /** @var int */
    private $wrap = 1;
    /** @var string */
    private $placement = '';
    /** @var string */
    private $plugins = '';
    /** @var int */
    private $required = 0;
    /** @var int */
    private $active = 0;
    /** @var string */
    private $dateModified = '';
    /** @var string */
    private $dateCreated = '';

    /**
     * Consent constructor.
     * @param int $id
     */
    public function __construct($id = 0) {
        if ((int)$id > 0) {
            $this->setId($id);
            $this->load();
        }
    }

    /**
     * @param bool $insertPrivacyPolicyLink
     * @return mixed
     */
    public static function getModalTitle($insertPrivacyPolicyLink = true) {
        $output = get_option(WP_GDPR_C_PREFIX . '_settings_consents_modal_title');
        if (empty($output)) {
            $output = __('Privacy Settings', WP_GDPR_C_SLUG);
        }
        $output = ($insertPrivacyPolicyLink === true) ? Integration::insertPrivacyPolicyLink($output) : $output;
        return apply_filters('wpgdprc_consents_modal_title', wp_kses($output, Helper::getAllowedHTMLTags()));
    }

    /**
     * @param bool $insertPrivacyPolicyLink
     * @return mixed
     */
    public static function getModalExplanationText($insertPrivacyPolicyLink = true) {
        $output = get_option(WP_GDPR_C_PREFIX . '_settings_consents_modal_explanation_text');
        if (empty($output)) {
            $output = __('This site uses functional cookies and external scripts to improve your experience. Which cookies and scripts are used and how they impact your visit is specified on the left. You may change your settings at any time. Your choices will not impact your visit.', WP_GDPR_C_SLUG);
        }
        $output = ($insertPrivacyPolicyLink === true) ? Integration::insertPrivacyPolicyLink($output) : $output;
        return apply_filters('wpgdprc_consents_modal_explanation_text', wp_kses($output, Helper::getAllowedHTMLTags()));
    }

    /**
     * @param bool $insertPrivacyPolicyLink
     * @return mixed
     */
    public static function getBarExplanationText($insertPrivacyPolicyLink = true) {
        $output = get_option(WP_GDPR_C_PREFIX . '_settings_consents_bar_explanation_text');
        if (empty($output)) {
            $output = __('This site uses functional cookies and external scripts to improve your experience.', WP_GDPR_C_SLUG);
        }
        $output = ($insertPrivacyPolicyLink === true) ? Integration::insertPrivacyPolicyLink($output) : $output;
        return apply_filters('wpgdprc_consents_bar_explanation_text', wp_kses($output, Helper::getAllowedHTMLTags()));
    }

    /**
     * @param array $consents
     * @return string
     */
    public static function output($consents = array()) {
        $output = '';
        if (!empty($consents)) {
            /** @var Consent $consent */
            foreach ($consents as $consent) {
                if ($consent->getWrap()) {
                    $output .= sprintf(
                        '<script type="text/javascript">%s</script>',
                        $consent->getSnippet()
                    );
                } else {
                    $output .= sprintf('%s', $consent->getSnippet());
                }
            }
        }
        return $output;
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
     * @return Consent[]
     */
    public function getList($filters = array(), $limit = 0, $offset = 0) {
        global $wpdb;
        $output = array();
        $query = "SELECT * FROM `" . self::getDatabaseTableName() . "` WHERE 1";
        $query .= Helper::getQueryByFilters($filters);
        $query .= sprintf(" AND `site_id` = '%d'", get_current_blog_id());
        $query .= " ORDER BY `date_modified` DESC";
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
        $this->setTitle($row->title);
        $this->setDescription($row->description);
        $this->setSnippet($row->snippet);
        $this->setWrap($row->wrap);
        $this->setPlacement($row->placement);
        $this->setPlugins($row->plugins);
        $this->setRequired($row->required);
        $this->setActive($row->active);
        $this->setDateModified($row->date_modified);
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
        $data = array(
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'snippet' => $this->getSnippet(),
            'wrap' => $this->getWrap(),
            'placement' => $this->getPlacement(),
            'plugins' => $this->getPlugins(),
            'required' => $this->getRequired(),
            'active' => $this->getActive(),
        );
        $dataTypes = array('%s', '%s', '%s', '%d', '%s', '%s', '%d', '%d');
        if ($this->exists($this->getId())) {
            $wpdb->update(
                self::getDatabaseTableName(),
                $data,
                array('ID' => $this->getId()),
                $dataTypes,
                array('%d')
            );
            return $this->getId();
        } else {
            $data['site_id'] = $this->getSiteId();
            $data['date_created'] = date_i18n('Y-m-d H:i:s');
            $dataTypes = array_merge($dataTypes, array('%d', '%s', '%d'));
            $result = $wpdb->insert(
                self::getDatabaseTableName(),
                $data,
                $dataTypes
            );
            if ($result !== false) {
                $this->setId($wpdb->insert_id);
                return $this->getId();
            }
        }
        return false;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete($id = 0) {
        if ((int)$id > 0) {
            global $wpdb;
            $result = $wpdb->delete(self::getDatabaseTableName(), array('ID' => $id), array('%d'));
            if ($result !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param int $id
     * @param string $action
     * @return string
     */
    public static function getActionUrl($id = 0, $action = 'manage') {
        return Helper::getPluginAdminUrl(
            'consents',
            array(
                'action' => $action,
                'id' => $id,
            )
        );
    }

    /**
     * @return array
     */
    public static function getPossibleCodeWraps() {
        return array(
            '1' => esc_html__('Wrap my code snippet with <script> tags', WP_GDPR_C_SLUG),
            '0' => __('Do not wrap my code snippet', WP_GDPR_C_SLUG)
        );
    }

    /**
     * @return array
     */
    public static function getPossiblePlacements() {
        return array(
            'head' => __('Head', WP_GDPR_C_SLUG),
            'footer' => __('Footer', WP_GDPR_C_SLUG)
        );
    }

    /**
     * @return null|Consent
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
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getSnippet() {
        return $this->snippet;
    }

    /**
     * @param string $snippet
     */
    public function setSnippet($snippet) {
        $this->snippet = $snippet;
    }

    /**
     * @return int
     */
    public function getWrap() {
        return $this->wrap;
    }

    /**
     * @param int $wrap
     */
    public function setWrap($wrap) {
        $this->wrap = $wrap;
    }

    /**
     * @return string
     */
    public function getPlacement() {
        return $this->placement;
    }

    /**
     * @param string $placement
     */
    public function setPlacement($placement) {
        $this->placement = $placement;
    }

    /**
     * @return string
     */
    public function getPlugins() {
        return $this->plugins;
    }

    /**
     * @param string $plugins
     */
    public function setPlugins($plugins) {
        $this->plugins = $plugins;
    }

    /**
     * @return int
     */
    public function getRequired() {
        return $this->required;
    }

    /**
     * @param int $required
     */
    public function setRequired($required) {
        $this->required = $required;
    }

    /**
     * @return int
     */
    public function getActive() {
        return $this->active;
    }

    /**
     * @param int $active
     */
    public function setActive($active) {
        $this->active = $active;
    }

    /**
     * @return string
     */
    public function getDateModified() {
        return $this->dateModified;
    }

    /**
     * @param string $dateModified
     */
    public function setDateModified($dateModified) {
        $this->dateModified = $dateModified;
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
        return $wpdb->base_prefix . 'wpgdprc_consents';
    }
}