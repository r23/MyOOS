<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

class url_rewrite
{
    public function transform_uri($param)
    {
        global $session;

        unset($path);
        unset($url);

        $uri = explode("index.php/", (string) $param);

        $path = $uri[1];
        $base = $uri[0];

        $url_array = explode('/', $path);

        $aContents = oos_get_content();

        if ((in_array('category', $url_array)) || (in_array($aContents['product_info'], $url_array) && in_array($url_array))) {
            $_filter = ['content', $aContents['shop'], $session->getName(), $session->getId()];

            $dbconn = & oosDBGetConn();
            $oostable = & oosDBGetTables();

            $nLanguageID = isset($_SESSION['language_id']) ? intval($_SESSION['language_id']) : DEFAULT_LANGUAGE_ID;

            $path = '';
            $extention = '.html';

            for ($i = 0; $i < count($url_array); $i++) {
                switch ($url_array[$i]) {
                    case 'category':
                        unset($category);
                        $category = '';
                        $i++;
                        if (preg_match('/[_0-9]/', $url_array[$i])) {
                            if ($category_array = explode('_', $url_array[$i])) {
                                foreach ($category_array as $value) {
                                    $categoriestable = $oostable['categories'];
                                    $categories_descriptiontable = $oostable['categories_description'];
                                    $category_result = $dbconn->Execute("SELECT c.categories_id, cd.categories_name FROM  $categoriestable c, $categories_descriptiontable cd WHERE c.categories_id = '" . intval($value) . "' AND c.categories_id = cd.categories_id AND cd.categories_languages_id = '" . intval($nLanguageID) . "'");
                                    $category .= oos_make_filename($category_result->fields['categories_name']) . '/';
                                }
                                $category = substr($category, 0, -1);
                                $category .= '-c-' .  $url_array[$i]. '/';
                            } else {
                                $category .= 'category/' . $url_array[$i] . '/';
                            }
                        }
                        $path .= $category;
                        break;

                    case 'products_id':
                        unset($product);
                        $i++;
                        if ($url_array[$i]) {
                            $products_descriptiontable = $oostable['products_description'];
                            $product_result = $dbconn->Execute("SELECT products_name FROM $products_descriptiontable WHERE products_id = '" . intval($url_array[$i]) . "' AND products_languages_id = '" .  intval($nLanguageID) . "'");
                            $product = oos_make_filename($product_result->fields['products_name']);
                            $path .= $product . '-p-' . $url_array[$i] . '/';
                        }
                        break;

                    case 'manufacturers_id':
                        unset($manufacturer);
                        $i++;
                        if ($url_array[$i]) {
                            $manufacturerstable = $oostable['manufacturers'];
                            $manufacturer_result = $dbconn->Execute("SELECT manufacturers_name FROM $manufacturerstable WHERE manufacturers_id = '" . intval($url_array[$i]) . "'");
                            $manufacturer = oos_make_filename($manufacturer_result->fields['manufacturers_name']);
                            $path .= $manufacturer . '-m-' . $url_array[$i] . '/';
                        }
                        break;

                    default:
                        if (!in_array($url_array[$i], $_filter)) {
                            $path .= $url_array[$i] . '/';
                        }
                        break;
                }
            }

            $pos = strpos($path, "-p-");
            if ($pos === false) {
                // $remove = array('-c-');
            } else {
                $remove = ['-m-', '-c-'];
            }
            $path = str_replace($remove, '-', $path);
            if (str_contains($path, '//')) {
                $path = str_replace('//', '/', $path);
            }
            if (str_ends_with($path, '/')) {
                $path = substr($path, 0, -1);
            }

            $url = $base . $path . $extention;
        } else {
            $url = $param;
        }

        return $url;
    }
}
