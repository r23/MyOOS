<?php

namespace WPGDPRC\Includes;

/**
 * Class SessionHelper
 * @package WPGDPRC\Includes
 */
class SessionHelper {
    /**
     * @return string
     */
    public static function getSessionId() {
        self::startSession();
        return session_id();
    }

    /**
     * Start the session if it has not started yet
     */
    public static function startSession() {
        if (!session_id()) {
            @session_start();
        }
    }

    /**
     * @param string $sessionId
     * @return bool
     */
    public static function checkSession($sessionId = '') {
        return self::getSessionId() === $sessionId;
    }

    /**
     * @param string $variable
     * @param string $value
     */
    public static function setSessionVariable($variable = '', $value = '') {
        self::startSession();
        $_SESSION[$variable] = $value;
    }

    /**
     * @param string $variable
     * @return bool
     */
    public static function getSessionVariable($variable = '') {
        self::startSession();
        return (isset($_SESSION[$variable])) ? $_SESSION[$variable] : false;
    }
}