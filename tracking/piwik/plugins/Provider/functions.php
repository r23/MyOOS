<?php
/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */

namespace Piwik\Plugins\Provider;

use Piwik\Common;
use Piwik\DataTable;
use Piwik\Piwik;

/**
 * Return hostname portion of a domain name
 *
 * @param string $in
 * @return string Host name, IP (if IP address didn't resolve), or Unknown
 */
function getHostnameName($in)
{
    if (empty($in) || strtolower($in) === 'ip') {
        return Piwik::translate('General_Unknown');
    }
    if (strpos($in, ' ') === false && ($positionDot = strpos($in, '.')) !== false) {
        return ucfirst(substr($in, 0, $positionDot));
    }
    return $in;
}

/**
 * Return URL for a given domain name
 *
 * @param string $in hostname
 * @return string URL
 */
function getHostnameUrl($in)
{
    if ($in == DataTable::LABEL_SUMMARY_ROW || empty($in) || strtolower($in) === 'ip') {
        return null;
    }

    // if the name is a valid hostname, return a URL - otherwise link to startpage
    if (filter_var($in, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
        return "http://" . $in . "/";
    } else {
        return "https://startpage.com/do/search?q=" . urlencode(getPrettyProviderName($in));
    }
}

/**
 * Return a pretty provider name for a given domain name
 *
 * @param string $in hostname
 * @return string Real ISP name, IP (if IP address didn't resolve), or Unknown
 */
function getPrettyProviderName($in)
{
    $providerName = getHostnameName($in);

    $prettyNames = Common::getProviderNames();

    if (is_array($prettyNames)
        && array_key_exists(strtolower($providerName), $prettyNames)
    ) {
        $providerName = $prettyNames[strtolower($providerName)];
    }

    return $providerName;
}
