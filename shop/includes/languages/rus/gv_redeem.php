<?php
/* ----------------------------------------------------------------------
   $Id: gv_redeem.php,v 1.4 2007/12/17 11:40:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2005 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_redeem.php,v 1.1.2.1 2003/05/15 23:04:32 wilt
   ----------------------------------------------------------------------
   The Exchange Project - Community Made Shopping!
   http://www.theexchangeproject.org

   Gift Voucher System v1.0
   Copyright (c) 2001,2002 Ian C Wilson
   http://www.phesis.org
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['navbar_title'] = 'Использовать талон';
$aLang['heading_title'] = 'Использовать талон';
$aLang['text_information'] = 'Дополнительную информацию о талонах читайте здесь <a href="' . oos_link($aModules['gv'], $aFilename['gv_faq']).'">'.GV_FAQ.'.</a>';
$aLang['text_invalid_gv'] = 'Код талона возможно недействителен или уже использован. По вопросам обращайтесь в <a href="' . oos_link($aModules['main'], $aFilename['contact_us']) . '">через нашу страницу.</a>.';
$aLang['text_valid_gv'] = 'Поздравляем с использоавнием талона ценностью %s';
?>