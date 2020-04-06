<?php

/**
 * Class: SentrySeverity.
 *
 * @author  Matías Halles <matias.halles@gmail.com>
 * @package phptek/sentry
 */

namespace PhpTek\Sentry\Adaptor;

use Sentry\Severity;

/**
 * SentryErrorTypes provides native error_types mapping for Sentry log level groups
 */
class SentryErrorTypes
{
    /** @var Severity */
    private $severity;

    /**
     * This constant contains the list of allowed enum values.
     */
    private const SEVERITIES = [
        Severity::FATAL,
        Severity::ERROR,
        Severity::WARNING,
        Severity::INFO,
        Severity::DEBUG,
    ];

    /** @var array */
    private static $error_type_mappings = [
        'fatal' => E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING,
        'error' => E_RECOVERABLE_ERROR | E_USER_ERROR,
        'warning' => E_DEPRECATED | E_USER_DEPRECATED | E_WARNING | E_WARNING,
        'info' => E_NOTICE | E_USER_NOTICE | E_STRICT,
        'debug' => E_ALL,
    ];

    /**
     * Provides a reverse mapping for error types based on an error level
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->severity = new Severity(mb_strtolower($value));
    }

    /**
     * Returns bitmask values for different sentry group log levels
     *
     * @return array
     */
    public function getErrorTypes()
    {
        $severities = [];

        foreach(self::SEVERITIES as $key) {
            $severities[] = self::$error_type_mappings[$key];
            if($key == $this->severity) {
                break;
            }
        }

        return $severities;
    }

    /**
     * Return single bitmask for all enabled error codes
     *
     * @return mixed
     */
    public function getErrorBitmask()
    {
        return array_reduce($this->getErrorTypes(), function($a, $b) { return $a | $b; }, 0);
    }

}
