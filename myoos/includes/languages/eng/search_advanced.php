<?php
/**
   ----------------------------------------------------------------------
   $Id: search_advanced.php,v 1.3 2007/06/12 16:51:19 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: advanced_search.php,v 1.13 2002/05/27 13:57:38 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

$aLang['navbar_title'] = 'Advanced Search';
$aLang['heading_title'] = 'Advanced Search';

$aLang['heading_search_criteria'] = 'Search Criteria';

$aLang['text_search_in_description'] = 'Search In Product Descriptions';
$aLang['entry_categories'] = 'Categories:';
$aLang['entry_include_subcategories'] = 'Include Subcategories';
$aLang['entry_manufacturers'] = 'Manufacturers:';
$aLang['entry_price_from'] = 'Price From:';
$aLang['entry_price_to'] = 'Price To:';
$aLang['entry_date_from'] = 'Date From:';
$aLang['entry_date_to'] = 'Date To:';

$aLang['text_search_help_link'] = 'Search Help [?]';

$aLang['text_all_categories'] = 'All Categories';
$aLang['text_all_manufacturers'] = 'All Manufacturers';

$aLang['heading_search_help'] = 'Search Help';
$aLang['text_search_help'] = 'Keywords may be separated by AND and/or OR statements for greater control of the search results.<br /><br />For example, <u>Microsoft AND mouse</u> would generate a result set that contain both words. However, for <u>mouse OR keyboard</u>, the result set returned would contain both or either words.<br /><br />Exact matches can be searched for by enclosing keywords in double-quotes.<br /><br />For example, <u>"notebook computer"</u> would generate a result set which match the exact string.<br /><br />Brackets can be used for further control on the result set.<br /><br />For example, <u>Microsoft and (keyboard or mouse or "visual basic")</u>.';
$aLang['text_close_window'] = 'Close';

$aLang['js_at_least_one_input'] = '* One of the following fields must be entered:\n    Keywords\n    Date Added From\n    Date Added To\n    Price From\n    Price To\n';
$aLang['js_invalid_from_date'] = '* Invalid From Date\n';
$aLang['js_invalid_to_date'] = '* Invalid To Date\n';
$aLang['js_to_date_less_than_from_date'] = '* To Date must be greater than or equal to From Date\n';
$aLang['js_price_from_must_be_num'] = '* Price From must be a number\n';
$aLang['js_price_to_must_be_num'] = '* Price To must be a number\n';
$aLang['js_price_to_less_than_price_from'] = '* Price To must be greater than or equal to Price From\n';
$aLang['js_invalid_keywords'] = '* Invalid keywords\n';
