<?php
/* ----------------------------------------------------------------------
   $Id: popup_newsfeed_view.php,v 1.1 2007/06/08 17:14:41 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 osCommerce
   Big Image Modification 2002/03/04
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';

  require 'includes/classes/class_rdf.php';
  $rdf = new oosRDF();

  $newsfeed_managertable = $oostable['newsfeed_manager'];
  $sql = "SELECT newsfeed_manager_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_numarticles, newsfeed_manager_refresh 
          FROM $newsfeed_managertable
          WHERE newsfeed_manager_id =  '" . $_GET['nmID'] . "'";
  $newsfeed_result = $dbconn->Execute($sql);
  $newsfeed = $newsfeed_result->fields;
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<title><?php echo $newsfeed['newsfeed_manager_name']; ?> - Administration [OOS]</title>
<style type="text/css"><!--
.fase4_rdf {
  font-family: Verdana, Arial, sans-serif; font-size: 10px; }
}

a.fase4_rdf:link {
  font-size: 11px; font-weight: normal; color: #FF9900;
}

a.fase4_rdf:hover {
  font-weight: bold; color: #808080;
}	

TD.fase4_rdf {
  font-family: Verdana, Arial, sans-serif; font-size: 10px; }
}
//--></style>
</head>
<body>

<?php 
  $rdf->use_dynamic_display(true);
  $rdf->set_Options(array('channel' => 'hidden',
                          'build' => 'hidden',
                          'cache_update' => 'hidden',
                          'textinput' => 'hidden',
                          'image' => ''));
  $rdf->set_max_item($newsfeed['newsfeed_manager_numarticles']);
  $rdf->set_refresh($newsfeed['newsfeed_manager_refresh']);
  $rdf->parse_RDF($newsfeed['newsfeed_manager_link']);
  $rdf->finish();

?>
</body>
</html>
<?php require 'includes/oos_nice_exit.php'; ?>