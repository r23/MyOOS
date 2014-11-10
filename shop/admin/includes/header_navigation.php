<?php
/* ----------------------------------------------------------------------
   $Id: header_navigation.php,v 1.1 2007/06/08 15:20:14 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );


  $menu_dhtml = MENU_DHTML;
  $box_files_list = array(  array('catalog'             , 'catalog.php', BOX_HEADING_CATALOG),
                            array('content-dhtml'       , 'content-dhtml.php', BOX_HEADING_CONTENT),
                            array('configuration-dhtml' , 'configuration-dhtml.php', BOX_HEADING_CONFIGURATION),
                            array('customers-dhtml'     , 'customers-dhtml.php' , BOX_HEADING_CUSTOMERS),
                            array('tools-dhtml'         , 'tools-dhtml.php' , BOX_HEADING_TOOLS),
                          );

   echo '<!-- Menu bar #2. --> <div class="menuBar" style="width:100%;">';
   foreach($box_files_list as $item_menu) {
     echo "<a class=\"menuButton\" href=\"\" onclick=\"return buttonClick(event, '".$item_menu[0]."Menu');\" onmouseover=\"buttonMouseover(event, '".$item_menu[0]."Menu');\">".$item_menu[2]."</a>" ;
   }
   echo "</div>";
foreach($box_files_list as $item_menu) require('includes/boxes/'. $item_menu[1] );


  $box_files_list = array(  array('reports'        , 'reports.php' , BOX_HEADING_REPORTS),
                            array('tools'          , 'tools.php' , BOX_HEADING_TOOLS),
                            array('rss_admin'      , 'rss_admin.php' , BOX_HEADING_RSS),
                            array('export'         , 'export.php' , BOX_HEADING_EXPORT),
                            array('customers'      , 'customers.php' , BOX_HEADING_CUSTOMERS),
                            array('ticket'         , 'ticket.php' , BOX_HEADING_TICKET),
                            array('affiliate'      , 'affiliate.php' , BOX_HEADING_AFFILIATE),
                            array('gv_admin'       , 'gv_admin.php' , BOX_HEADING_GV_ADMIN),
                            array('administrator'  , 'administrator.php' , BOX_HEADING_ADMINISTRATORS),
                            array('configuration'  , 'configuration.php', BOX_HEADING_CONFIGURATION),
                            array('modules'        , 'modules.php' , BOX_HEADING_MODULES),
                            array('plugins'        , 'plugins.php', BOX_HEADING_PLUGINS),
                            array('taxes'          , 'taxes.php' , BOX_HEADING_LOCATION_AND_TAXES),
                            array('localization'   , 'localization.php' , BOX_HEADING_LOCALIZATION),
                            array('content'        , 'content.php', BOX_HEADING_CONTENT),
                            array('links'          , 'links.php' , BOX_HEADING_LINKS),
                            array('newsfeed'       , 'newsfeed.php', BOX_HEADING_NEWSFEED),
                            array('information'    , 'information.php', BOX_HEADING_INFORMATION),
                         );

foreach($box_files_list as $item_menu) require('includes/boxes/'. $item_menu[1] );

?>