<?php
/* ----------------------------------------------------------------------
   $Id: search_advanced.php,v 1.1 2007/06/13 15:54:26 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:
  
   File: advanced_search.php,v 1.16 2002/11/12 00:45:21 dgw_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['navbar_title'] = 'Bsqueda Avanzada';
$aLang['heading_title'] = 'Bsqueda Avanzada';

$aLang['heading_search_criteria'] = 'Bsqueda Avanzada';

$aLang['text_search_in_description'] = 'Buscar tambien en la descripcion';
$aLang['entry_categories'] = 'Categor�s:';
$aLang['entry_include_subcategories'] = 'Incluir Subcategor�s';
$aLang['entry_manufacturers'] = 'Fabricante:';
$aLang['entry_price_from'] = 'Desde precio:';
$aLang['entry_price_to'] = 'a precio:';
$aLang['entry_date_from'] = 'De fecha de alta:';
$aLang['entry_date_to'] = 'a alta:';

$aLang['text_search_help_link'] = '<u>Ayuda</u> [?]';

$aLang['text_all_categories'] = 'Todas';
$aLang['text_all_manufacturers'] = 'Todos';

$aLang['heading_search_help'] = 'Consejos para Busqueda Avanzada';
$aLang['text_search_help'] = 'El motor de busqueda le permite hacer una busqueda por palabras clave en el modelo, nombre y descripcion del producto y en el nombre del fabricante.<br /><br />Cuando haga una busqueda por palabras o frases clave, puede separar estas con los operadores logicos AND y OR. Por ejemplo, puede hacer una busqueda por <u>microsoft AND raton</u>. Esta busqueda daria como resultado los productos que contengan ambas palabras. Por el contrario, si teclea  <u>raton OR teclado</u>, conseguira una lista de los productos que contengan las dos o solo una de las palabras. Si no se separan las palabras o frases clave con AND o con OR, la busqueda se hara usando por defecto el operador logico AND.<br /><br />Puede realizar busquedas exactas de varias palabras encerrandolas entre comillas. Por ejemplo, si busca <u>"ordenador portatil"</u>, obtendras una lista de productos que tengan exactamente esa cadena en ellos.<br /><br />Se pueden usar paratensis para controlar el orden de las operaciones logicas. Por ejemplo, puede introducir <u>microsoft and (teclado or raton or "visual basic")</u>.';
$aLang['text_close_window'] = '<u>Cerrar Ventana</u> [x]';

$aLang['js_at_least_one_input'] = '* Uno de los siguientes campos debe ser introducido:\n    Palabras Clave\n    Fecha de Alta Desde\n    Fecha de Alta Hasta\n    Precio Desde\n    Precio Hasta\n';
$aLang['js_invalid_from_date'] = '* La Fecha de Alta Desde es invalida\n';
$aLang['js_invalid_to_date'] = '* La Fecha de Alta Hasta es invalida\n';
$aLang['js_to_date_less_than_from_date'] = '* Fecha de Alta Hasta debe ser mayor que Fecha de Alta Desde\n';
$aLang['js_price_from_must_be_num'] = '* El Precio Desde debe ser nmerico\n';
$aLang['js_price_to_must_be_num'] = '* El Precio Hasta debe ser nmerico\n';
$aLang['js_price_to_less_than_price_from'] = '* Precio Hasta debe ser mayor o igual que Precio Desde\n';
$aLang['js_invalid_keywords'] = '* Palabras clave incorrectas\n';
?>
