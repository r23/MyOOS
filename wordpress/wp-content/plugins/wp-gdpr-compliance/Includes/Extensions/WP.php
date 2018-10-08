<?php

namespace WPGDPRC\Includes\Extensions;

use WPGDPRC\Includes\Helper;
use WPGDPRC\Includes\Integration;

/**
 * Class WP
 * @package WPGDPRC\Includes\Extensions
 */
class WP {
    const ID = 'wordpress';
    /** @var null */
    private static $instance = null;

    /**
     * @param string $submitField
     * @return string
     */
    public function addField($submitField = '') {
        $field = apply_filters(
            'wpgdprc_wordpress_field',
            '<p class="wpgdprc-checkbox"><label><input type="checkbox" name="wpgdprc" id="wpgdprc" value="1" /> ' . Integration::getCheckboxText(self::ID) . ' <abbr class="wpgdprc-required" title="' . Integration::getRequiredMessage(self::ID) . '">*</abbr></label></p>',
            $submitField
        );
        return $field . $submitField;
    }

    public function checkPost() {
        if (!isset($_POST['wpgdprc'])) {
            wp_die(
                '<p>' . sprintf(
                    __('<strong>ERROR</strong>: %s', WP_GDPR_C_SLUG),
                    Integration::getErrorMessage(self::ID)
                ) . '</p>',
                __('Comment Submission Failure'),
                array('back_link' => true)
            );
        }
    }

    /**
     * @param int $commentId
     */
    public function addAcceptedDateToCommentMeta($commentId = 0) {
        if (isset($_POST['wpgdprc']) && !empty($commentId)) {
            add_comment_meta($commentId, '_wpgdprc', time());
        }
    }

    /**
     * @param array $columns
     * @return array
     */
    public function displayAcceptedDateColumnInCommentOverview($columns = array()) {
        $columns['wpgdprc-date'] = apply_filters('wpgdprc_accepted_date_column_in_comment_overview', __('GDPR Accepted On', WP_GDPR_C_SLUG));
        return $columns;
    }

    /**
     * @param string $column
     * @param int $commentId
     * @return string
     */
    public function displayAcceptedDateInCommentOverview($column = '', $commentId = 0) {
        if ($column === 'wpgdprc-date') {
            $date = get_comment_meta($commentId, '_wpgdprc', true);
            $value = (!empty($date)) ? Helper::localDateFormat(get_option('date_format') . ' ' . get_option('time_format'), $date) : __('Not accepted.', WP_GDPR_C_SLUG);
            echo apply_filters('wpgdprc_accepted_date_in_comment_overview', $value, $commentId);
        }
        return $column;
    }

    /**
     * @return null|WP
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}