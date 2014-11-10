<?php
/* ----------------------------------------------------------------------
   $Id: user_login.php,v 1.1 2007/06/13 15:54:26 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:
  
   File: login.php,v 1.13 2002/11/12 00:45:21 dgw_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

if (isset($_GET['origin']) && ($_GET['origin'] == $aFilename['checkout_payment'])) {
  $aLang['navbar_title'] = 'Realizar Pedido';
  $aLang['heading_title'] = 'Comprar aqui es facil.';
} else {
  $aLang['navbar_title'] = 'Entrar';
  $aLang['heading_title'] = 'Dejame Entrar!';
}

$aLang['heading_new_customer'] = 'Nuevo Cliente';
$aLang['text_new_customer'] = 'Soy un nuevo cliente.';
$aLang['text_new_customer_introduction'] = 'Al crear una cuenta en ' . STORE_NAME . ' podr�realizar sus compras rapidamente, revisar el estado de sus pedidos y consultar sus operaciones anteriores.';

$aLang['heading_returning_customer'] = 'Ya Soy Cliente';
$aLang['text_returning_customer'] = 'He comprado otras veces.';

$aLang['entry_remember_me'] = 'Remember me<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:win_autologon(\'' . oos_href_link($aModules['main'], $aFilename['info_autologon']) . '\';"><b><u>Read this first!</u></b></a>';
$aLang['text_password_forgotten'] = 'Ha olvidado su contrase�? Siga este enlace y se la enviamos.';

$aLang['text_login_error'] = '<font color="#ff0000"><b>ERROR:</b></font> El \'E-Mail\' y/o \'Contrase�\' no figuran en nuestros datos.';
$aLang['text_visitors_cart'] = '<font color="#ff0000"><b>NOTA:</b></font> El contenido de su &quot;Cesta de Visitante&quot; ser�a�dido a su &quot;Cesta de Asociado&quot; una vez que haya entrado. <a href="javascript:session_win(\'' . oos_href_link($aModules['main'], $aFilename['info_shopping_cart']) . '\';">[Mas Informacion]</a>';
?>
