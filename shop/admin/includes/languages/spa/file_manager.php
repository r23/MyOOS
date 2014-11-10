<?php
/* ----------------------------------------------------------------------
   $Id: file_manager.php,v 1.3 2007/06/13 16:51:45 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: file_manager.php,v 1.14 2002/08/19 01:45:58 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


define('HEADING_TITLE', 'Administrador de Archivos');

define('TABLE_HEADING_FILENAME', 'Nombre');
define('TABLE_HEADING_SIZE', 'Tama�');
define('TABLE_HEADING_PERMISSIONS', 'Permisos');
define('TABLE_HEADING_USER', 'Usuario');
define('TABLE_HEADING_GROUP', 'Grupo');
define('TABLE_HEADING_LAST_MODIFIED', 'Modificado');
define('TABLE_HEADING_ACTION', 'Accion');

define('TEXT_INFO_HEADING_UPLOAD', 'Subir');
define('TEXT_FILE_NAME', 'Nombre:');
define('TEXT_FILE_SIZE', 'Tama�:');
define('TEXT_FILE_CONTENTS', 'Contenido:');
define('TEXT_LAST_MODIFIED', 'Ultima Modificacion:');
define('TEXT_NEW_FOLDER', 'Nueva Carpeta');
define('TEXT_NEW_FOLDER_INTRO', 'Introduzca el nombre de la carpeta nueva:');
define('TEXT_DELETE_INTRO', 'Seguro que desea eliminar este fichero?');
define('TEXT_UPLOAD_INTRO', 'Seleccione los ficheros a subir.');

define('ERROR_DIRECTORY_NOT_WRITEABLE', 'Error: No puedo escribir en este directorio. Asigne los permisos adecuados a: %s');
define('ERROR_FILE_NOT_WRITEABLE', 'Error: No puedo escribir en este fichero. Asigne los permisos adecuados a: %s');
define('ERROR_DIRECTORY_NOT_REMOVEABLE', 'Error: No puedo eliminar el directorio. Asigne los permisos adecuados a: %s');
define('ERROR_FILE_NOT_REMOVEABLE', 'Error: No puedo eliminar este fichero. Asigne los permisos adecuados a: %s');
define('ERROR_DIRECTORY_DOES_NOT_EXIST', 'Error: Directory does not exist: %s');
?>
