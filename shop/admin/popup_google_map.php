<?php
/* ----------------------------------------------------------------------
   $Id: popup_google_map.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';

  include '../includes/classes/class_maps.php';

  $query = isset($_GET['query']) ? $_GET['query'] : 'barcelona';

  $map = new SPAF_Maps($query);

  $map->setAPIKey(GOOGLE_MAP_API_KEY);
  $map->printHeaderJS();
?>
</head>
<body>

<table cellspacing="0" cellpadding="10" border="0" bgcolor="#c4c4c4">
<tr><td bgcolor="#a4a4a4"><?php $map->showLocationControl(); ?></td></tr>
<tr><td><?php $map->showMap(); ?></td></tr>
</table>

<br />
</body>
</html>
<?php require 'includes/oos_nice_exit.php'; ?>