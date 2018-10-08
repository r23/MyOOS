<?php

namespace WPGDPRC\Includes;

/**
 * Class Filter
 * @package WPGDPRC\Includes
 */
class Filter {
    /** @var null */
    private static $instance = null;

    /**
     * @return null|Filter
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}