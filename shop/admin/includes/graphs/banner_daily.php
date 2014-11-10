<?php
/* ----------------------------------------------------------------------
   $Id: banner_daily.php,v 1.1 2007/06/08 14:02:06 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: banner_daily.php,v 1.2 2002/05/09 14:09:38 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  include 'includes/classes/class_phplot.php';

  $year = (($_GET['year']) ? $_GET['year'] : date('Y'));
  $month = (($_GET['month']) ? $_GET['month'] : date('n'));

  $days = (date('t', mktime(0,0,0,$month))+1);
  $stats = array();
  for ($i=1; $i<$days; $i++) {
    $stats[] = array($i, '0', '0');
  }

  $banner_stats_result = $dbconn->Execute("SELECT dayofmonth(banners_history_date) as banner_day, banners_shown as value, banners_clicked as dvalue FROM " . $oostable['banners_history'] . " WHERE banners_id = '" . $banner_id . "' AND month(banners_history_date) = '" . $month . "' AND year(banners_history_date) = '" . $year . "'");
  while ($banner_stats = $banner_stats_result->fields) {
    $stats[($banner_stats['banner_day']-1)] = array($banner_stats['banner_day'], (($banner_stats['value']) ? $banner_stats['value'] : '0'), (($banner_stats['dvalue']) ? $banner_stats['dvalue'] : '0'));

    // Move that ADOdb pointer!
    $banner_stats_result->MoveNext();
  }

  $graph = new PHPlot(600, 350, 'images/graphs/banner_daily-' . $banner_id . '.' . $banner_extension);

  $graph->SetFileFormat($banner_extension);
  $graph->SetIsInline(1);
  $graph->SetPrintImage(0);

  $graph->SetSkipBottomTick(1);
  $graph->SetDrawYGrid(1);
  $graph->SetPrecisionY(0);
  $graph->SetPlotType('lines');

  $graph->SetPlotBorderType('left');
  $graph->SetTitleFontSize('4');
  $graph->SetTitle(sprintf(TEXT_BANNERS_DAILY_STATISTICS, $banner['banners_title'], strftime('%B', mktime(0,0,0,$month)), $year));

  $graph->SetBackgroundColor('white');

  $graph->SetVertTickPosition('plotleft');
  $graph->SetDataValues($stats);
  $graph->SetDataColors(array('blue','red'),array('blue', 'red'));

  $graph->DrawGraph();

  $graph->PrintImage();
?>
