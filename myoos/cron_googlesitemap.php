<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   Google XML Sitemap Feed Cron Script

   Bobby Easland
   Copyright 2005, Bobby Easland
   http://www.oscommerce-freelancers.com/
   ----------------------------------------------------------------------
   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */


/**
 * Set the error reporting level. Unless you have a special need, E_ALL is a
 * good level for error reporting.
 */
error_reporting(E_ALL);
// error_reporting(E_ALL & ~E_STRICT);

//setting basic configuration parameters
if (function_exists('ini_set')) {
    ini_set('session.use_trans_sid', 0);
    ini_set('url_rewriter.tags', '');
    ini_set('xdebug.show_exception_trace', 0);
    ini_set('magic_quotes_runtime', 0);
    // ini_set('display_errors', false);
}


use Symfony\Component\HttpFoundation\Request;

$autoloader = include_once __DIR__ . '/vendor/autoload.php';
$request = Request::createFromGlobals();

define('MYOOS_INCLUDE_PATH', __DIR__ == '/' ? '' : __DIR__);

define('OOS_VALID_MOD', true);

require_once MYOOS_INCLUDE_PATH . '/includes/main.php';
require_once MYOOS_INCLUDE_PATH . '/includes/lib/snoopy/snoopy.class.php';

class MyOOS_Utilities
{
    /**
     * Opens a remote file using  Snoopy
     *
     * @param  $url      The URL to open
     * @param  $method   get or post
     * @param  $postData An array with key=>value paris
     * @param  $timeout  Timeout for the request, by default 10
     * @return mixed False on error, the body of the response on success
     */
    public static function RemoteOpen($url, $method = 'get', $postData = null, $timeout = 10)
    {
        $oS = new Snoopy();

        $oS->read_timeout = $timeout;

        if ($method == 'get') {
            $oS->fetch($url);
        } else {
            $oS->submit($url, $postData);
        }

        if ($oS->status != "200") {
            trigger_error('Snoopy Web Request failed: Status: ' . $oS->status . "; Content: " . htmlspecialchars((string)$oS->results), E_USER_NOTICE);
        }

        return $oS->results;
    }
}


//Settings - changes made here
define('GOOGLE_SITEMAP_COMPRESS', '0'); // Option to compress the files

define('GOOGLE_SITEMAP_PROD_CHANGE_FREQ', 'weekly'); // Option for change frequency of products
define('GOOGLE_SITEMAP_CAT_CHANGE_FREQ', 'weekly'); // Option for change frequency of categories


//prevent script from running more than once a day
$configurationtable = $oostable['configuration'];
$sql = "SELECT configuration_value FROM $configurationtable WHERE configuration_key = 'CRON_GOOGLE_RUN'";
$prevent_result = $dbconn->Execute($sql);

if ($prevent_result->RecordCount() > 0) {
    $prevent = $prevent_result->fields;
    if ($prevent['configuration_value'] == date("Ymd")) {
        die('Halt! Already executed - should not execute more than once a day.');
    }
}


require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_googlesitemap.php';

$oSitemap = new GoogleSitemap();

$submit = true;
echo '<pre>';

if ($oSitemap->GenerateProductSitemap()) {
    echo 'Generated Google Product Sitemap Successfully' . "\n\n";
} else {
    $submit = false;
    echo 'ERROR: Google Product Sitemap Generation FAILED!' . "\n\n";
}

if ($oSitemap->GenerateCategorySitemap()) {
    echo 'Generated Google Category Sitemap Successfully' . "\n\n";
} else {
    $submit = false;
    echo 'ERROR: Google Category Sitemap Generation FAILED!' . "\n\n";
}

if ($oSitemap->GenerateSitemapIndex()) {
    echo 'Generated Google Sitemap Index Successfully' . "\n\n";
} else {
    $submit = false;
    echo 'ERROR: Google Sitemap Index Generation FAILED!' . "\n\n";
}


if ($submit) {
    if ($prevent_result->RecordCount() > 0) {
        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("UPDATE $configurationtable SET configuration_value = '" . date("Ymd") . "' WHERE configuration_key = 'CRON_GOOGLE_RUN'");
    } else {
        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id) VALUES ('CRON_GOOGLE_RUN', '" . date("Ymd") . "', '6')");
    }

    echo 'CONGRATULATIONS! All files generated successfully.' . "\n\n";

    echo 'Here is your sitemap index: ' .$oSitemap->base_url . 'sitemapindex.xml' . "\n";
    echo 'Here is your product sitemap: ' . $oSitemap->base_url . 'sitemapproducts.xml' . "\n";
    echo 'Here is your category sitemap: ' . $oSitemap->base_url . 'sitemapcategories.xml' . "\n";

    $pingUrl = $oSitemap->base_url . 'sitemapindex.xml';

    //Ping Google
    $sPingUrl = "http://www.google.com/webmasters/sitemaps/ping?sitemap=" . urlencode($pingUrl);
    $pingres = MyOOS_Utilities::RemoteOpen($sPingUrl);

    if ($pingres == null || $pingres === false) {
        trigger_error("Failed to ping Google: " . htmlspecialchars(strip_tags((string)$pingres)), E_USER_NOTICE);
    }

    //Ping Bing
    $sPingUrl = "http://www.bing.com/webmaster/ping.aspx?siteMap=" . urlencode($pingUrl);
    $pingres = MyOOS_Utilities::RemoteOpen($sPingUrl);
    if ($pingres == null || $pingres === false || !str_contains((string) $pingres, "Thanks for submitting your sitemap")) {
        trigger_error("Failed to ping Bing: " . htmlspecialchars(strip_tags((string)$pingres)), E_USER_NOTICE);
    }
} else {
    print_r($oSitemap->debug);
}
echo '</pre>';
require_once MYOOS_INCLUDE_PATH . '/includes/nice_exit.php';
