<?php

namespace Psr\Log;

/**
 * Describes log levels.
 */
class LogLevel
{
    final public const EMERGENCY = 'emergency';
    final public const ALERT     = 'alert';
    final public const CRITICAL  = 'critical';
    final public const ERROR     = 'error';
    final public const WARNING   = 'warning';
    final public const NOTICE    = 'notice';
    final public const INFO      = 'info';
    final public const DEBUG     = 'debug';
}
