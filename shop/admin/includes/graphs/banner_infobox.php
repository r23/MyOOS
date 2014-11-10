<?php
/* ----------------------------------------------------------------------
   $Id: banner_infobox.php,v 1.1 2007/06/08 14:02:06 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: banner_infobox.php,v 1.2 2002/05/09 14:09:39 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  include 'includes/classes/class_phplot.php';

  $stats = array();
  $banner_stats_result = $dbconn->Execute("SELECT dayofmonth(banners_history_date) as name, banners_shown as value, banners_clicked as dvalue FROM " . $oostable['banners_history'] . " WHERE banners_id = '" . $banner_id . "' AND to_days(now()) - to_days(banners_history_date) < " . $days . " ORDER BY banners_history_date");
  while ($banner_stats = $banner_stats_result->fields) {
    $stats[] = array($banner_stats['name'], $banner_stats['value'], $banner_stats['dvalue']);

    // Move that ADOdb pointer!
    $banner_stats_result->MoveNext();
  }

  if (count($stats) < 1) $stats = array(array(date('j'), 0, 0));

  $graph = new PHPlot(200, 220, 'images/graphs/banner_infobox-' . $banner_id . '.' . $banner_extension);

  $graph->SetFileFormat($banner_extension);
  $graph->SetIsInline(1);
  $graph->SetPrintImage(0);

  $graph->draw_vert_ticks = 0;
  $graph->SetSkipBottomTick(1);
  $graph->SetDrawXDataLabels(0);
  $graph->SetDrawYGrid(0);
  $graph->SetPlotType('bars');
  $graph->SetDrawDataLabels(1);
  $graph->SetLabelScalePosition(1);
  $graph->SetMarginsPixels(15,15,15,30);

  $graph->SetTitleFontSize('4');
  $graph->SetTitle('3 Day Statistics');

  $graph->SetDataValues($stats);
  $graph->SetDataColors(array('blue','red'),array('blue', 'red'));

  $graph->DrawGraph();

  $graph->PrintImage();
?>
