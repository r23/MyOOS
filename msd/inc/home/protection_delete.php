<?php
/* ----------------------------------------------------------------------

   MyOOS [Dumper]
   http://www.oos-shop.de/

   Copyright (c) 2016 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   MySqlDumper
   http://www.mysqldumper.de

   Copyright (C)2004-2011 Daniel Schlichtholz (admin@mysqldumper.de)
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

if (!defined('MSD_VERSION')) die('No direct access.');
@unlink($config['paths']['root'].'.htaccess');
@unlink($config['paths']['root'].'.htpasswd');
$action='status';

// todo -> give user info about success or failure of deleting action
