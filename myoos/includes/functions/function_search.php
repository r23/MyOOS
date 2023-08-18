<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: general.php,v 1.212 2003/02/17 07:55:54 hpdl
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

 /**
  * Return all subcategory IDs
  *
  * @param $aSubcategories
  * @param $nParentId
  */
function oos_get_subcategories(&$aSubcategories, $nParentId = 0)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categoriestable = $oostable['categories'];
    $query = "SELECT categories_id
              FROM $categoriestable
              WHERE parent_id = '" . intval($nParentId) . "'";
    $result = $dbconn->Execute($query);

    while ($subcategories = $result->fields) {
        $aSubcategories[is_countable($aSubcategories) ? count($aSubcategories) : 0] = $subcategories['categories_id'];
        if ($subcategories['categories_id'] != $nParentId) {
            oos_get_subcategories($aSubcategories, $subcategories['categories_id']);
        }

        // Move that ADOdb pointer!
        $result->MoveNext();
    }
}


/**
 * Parse search string into indivual objects
 *
 * @param string $search_str
 * @return array|bool
 */
function &oos_parse_search_string(string $sSearch = ''): array|bool
{
    $sSearch = trim((string) strtolower($sSearch));

    // Break up $sSearch on whitespace; quoted string will be reconstructed later
    $pieces = preg_split('/[[:space:]]+/', $sSearch);
    $objects = [];
    $tmpstring = '';
    $flag = '';

    for ($k=0; $k<(is_countable($pieces) ? count($pieces) : 0); $k++) {
        while (str_starts_with($pieces[$k], '(')) {
            $objects[] = '(';
            if (strlen($pieces[$k] ?? '') > 1) {
                $pieces[$k] = substr($pieces[$k], 1);
            } else {
                $pieces[$k] = '';
            }
        }

        $post_objects = [];

        while (str_ends_with($pieces[$k], ')')) {
            $post_objects[] = ')';
            if (strlen($pieces[$k] ?? '') > 1) {
                $pieces[$k] = substr($pieces[$k], 0, -1);
            } else {
                $pieces[$k] = '';
            }
        }

        // Check individual words	
        if (!str_contains($pieces[$k], '"')) {
            $objects[] = trim((string) $pieces[$k]);

            for ($j=0; $j<count($post_objects); $j++) {
                $objects[] = $post_objects[$j];
            }
        } else {

            /*
            This means that the $piece is either the beginning or the end of a string.
            So, we'll slurp up the $pieces and stick them together until we get to the
            end of the string or run out of pieces.
            */

            // Add this word to the $tmpstring, starting the $tmpstring
            $tmpstring = trim((string) preg_match('/"/', ' ', $pieces[$k]));

            // Check for one possible exception to the rule. That there is a single quoted word.
            if (str_ends_with($pieces[$k], '"')) {
                // Turn the flag off for future iterations
                $flag = 'off';

                $objects[] = trim((string) $pieces[$k]);

                for ($j=0; $j<count($post_objects); $j++) {
                    $objects[] = $post_objects[$j];
                }

                unset($tmpstring);

                // Stop looking for the end of the string and move onto the next word.
                continue;
            }

            // Otherwise, turn on the flag to indicate no quotes have been found attached to this word in the string.
            $flag = 'on';

            // Move on to the next word
            $k++;

            // Keep reading until the end of the string as long as the $flag is on

            while (($flag == 'on') && ($k < (is_countable($pieces) ? count($pieces) : 0))) {
                while (str_ends_with($pieces[$k], ')')) {
                    $post_objects[] = ')';
                    if (strlen($pieces[$k] ?? '') > 1) {
                        $pieces[$k] = substr($pieces[$k], 0, -1);
                    } else {
                        $pieces[$k] = '';
                    }
                }

                // If the word doesn't end in double quotes, append it to the $tmpstring.
                if (!str_ends_with($pieces[$k], '"')) {
                    // Tack this word onto the current string entity
                    $tmpstring .= ' ' . $pieces[$k];

                    // Move on to the next word
                    $k++;
                    continue;
                } else {
                    /*
                                If the $piece ends in double quotes, strip the double quotes, tack the
                                $piece onto the tail of the string, push the $tmpstring onto the $haves,
                                kill the $tmpstring, turn the $flag "off", and return.
                    */
                    $sTmp = preg_replace('/"/', ' ', $pieces[$k]);
                    $tmpstring .= ' ' . trim((string) $sTmp);


                    // Push the $tmpstring onto the array of stuff to search for
                    $objects[] = trim((string) $tmpstring);

                    for ($j=0; $j<count($post_objects); $j++) {
                        $objects[] = $post_objects[$j];
                    }

                    unset($tmpstring);

                    // Turn off the flag to exit the loop
                    $flag = 'off';
                }
            }
        }
    }

    // add default logical operators if needed
    $temp = [];
    for ($i=0; $i<(count($objects)-1); $i++) {
        $temp[count($temp)] = $objects[$i];

        if (($objects[$i] != 'and')
            && ($objects[$i] != 'or')
            && ($objects[$i] != '(')
            && ($objects[$i] != ')')
            && ($objects[$i+1] != 'and')
            && ($objects[$i+1] != 'or')
            && ($objects[$i+1] != '(')
            && ($objects[$i+1] != ')')
        ) {
            $temp[count($temp)] = ADVANCED_SEARCH_DEFAULT_OPERATOR;
        }
    }
    $temp[count($temp)] = $objects[$i];
    $objects = $temp;

    $keyword_count = 0;
    $operator_count = 0;
    $balance = 0;
    for ($i=0; $i<count($objects); $i++) {
        if ($objects[$i] == '(') {
            $balance --;
        }
        if ($objects[$i] == ')') {
            $balance ++;
        }
        if (($objects[$i] == 'and') || ($objects[$i] == 'or')) {
            $operator_count ++;
        } elseif (($objects[$i]) && ($objects[$i] != '(') && ($objects[$i] != ')')) {
            $keyword_count ++;
        }
    }

    if (($operator_count < $keyword_count) && ($balance == 0)) {
        return $objects;
    } else {
        return false;
    }
}


 /**
  * Check date
  *
  * @param  $date_to_check
  * @param  $format_string
  * @param  $date_array
  * @return boolean
  */
function oos_checkdate($date_to_check, $format_string, &$date_array)
{
    $separators = ['-', ' ', '/', '.'];
    $month_abbr = ['jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec'];
    $no_of_days = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    $format_string = strtolower($format_string ?? '');

    if (strlen($date_to_check ?? '') != strlen($format_string ?? '')) {
        return false;
    }

    $size = count($separators);
    for ($i=0; $i<$size; $i++) {
        $pos_separator = strpos((string) $date_to_check, $separators[$i]);
        if ($pos_separator != false) {
            $date_separator_idx = $i;
            break;
        }
    }

    for ($i=0; $i<$size; $i++) {
        $pos_separator = strpos($format_string, $separators[$i]);
        if ($pos_separator != false) {
            $format_separator_idx = $i;
            break;
        }
    }

    if ($date_separator_idx != $format_separator_idx) {
        return false;
    }

    if ($date_separator_idx != -1) {
        $format_string_array = explode($separators[$date_separator_idx], $format_string);
        if (count($format_string_array) != 3) {
            return false;
        }

        $date_to_check_array = explode($separators[$date_separator_idx], (string) $date_to_check);
        if (count($date_to_check_array) != 3) {
            return false;
        }

        $size = count($format_string_array);
        for ($i=0; $i<$size; $i++) {
            if ($format_string_array[$i] == 'mm' || $format_string_array[$i] == 'mmm') {
                $month = $date_to_check_array[$i];
            }
            if ($format_string_array[$i] == 'dd') {
                $day = $date_to_check_array[$i];
            }
            if (($format_string_array[$i] == 'yyyy') || ($format_string_array[$i] == 'aaaa')) {
                $year = $date_to_check_array[$i];
            }
        }
    } else {
        if (strlen($format_string ?? '') == 8 || strlen($format_string ?? '') == 9) {
            $pos_month = strpos($format_string, 'mmm');
            if ($pos_month != false) {
                $month = substr((string) $date_to_check, $pos_month, 3);
                $size = count($month_abbr);
                for ($i=0; $i<$size; $i++) {
                    if ($month == $month_abbr[$i]) {
                        $month = $i;
                        break;
                    }
                }
            } else {
                $month = substr((string) $date_to_check, strpos($format_string, 'mm'), 2);
            }
        } else {
            return false;
        }

        $day = substr((string) $date_to_check, strpos($format_string, 'dd'), 2);
        $year = substr((string) $date_to_check, strpos($format_string, 'yyyy'), 4);
    }

    if (strlen($year ?? '') != 4) {
        return false;
    }

    if (!settype($year, 'integer') || !settype($month, 'integer') || !settype($day, 'integer')) {
        return false;
    }

    if ($month > 12 || $month < 1) {
        return false;
    }

    if ($day < 1) {
        return false;
    }

    if (oos_is_leap_year($year)) {
        $no_of_days[1] = 29;
    }

    if ($day > $no_of_days[$month - 1]) {
        return false;
    }

    $date_array = [$year, $month, $day];

    return true;
}


 /**
  * Check if year is a leap year
  *
  * @param  $year
  * @return boolean
  */
function oos_is_leap_year($year)
{
    if ($year % 100 == 0) {
        if ($year % 400 == 0) {
            return true;
        }
    } else {
        if (($year % 4) == 0) {
            return true;
        }
    }

    return false;
}
