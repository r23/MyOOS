<?php
/* ----------------------------------------------------------------------
   $Id: banner_yearly.php,v 1.1 2007/06/08 14:02:06 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: banner_yearly.php,v 1.3 2002/05/09 18:28:46 dgw_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  include 'includes/classes/class_phplot.php';

  $stats = array(array('0', '0', '0'));
  $banner_stats_result = $dbconn->Execute("SELECT year(banners_history_date) as year, sum(banners_shown) as value, sum(banners_clicked) as dvalue FROM " . $oostable['banners_history'] . " WHERE banners_id = '" . $banner_id . "' group by year");
  while ($banner_stats = $banner_stats_result->fields) {
    $stats[] = array($banner_stats['year'], (($banner_stats['value']) ? $banner_stats['value'] : '0'), (($banner_stats['dvalue']) ? $banner_stats['dvalue'] : '0'));

    // Move that ADOdb pointer!
    $banner_stats_result->MoveNext();
  }

  $graph = new PHPlot(600, 350, 'images/graphs/banner_yearly-' . $banner_id . '.' . $banner_extension);

  $graph->SetFileFormat($banner_extension);
  $graph->SetIsInline(1);
  $graph->SetPrintImage(0);

  $graph->SetSkipBottomTick(1);
  $graph->SetDrawYGrid(1);
  $graph->SetPrecisionY(0);
  $graph->SetPlotType('lines');

  $graph->SetPlotBorderType('left');
  $graph->SetTitleFontSize('4');
  $graph->SetTitle(sprintf(TEXT_BANNERS_YEARLY_STATISTICS, $banner['banners_title']));

  $graph->SetBackgroundColor('white');

  $graph->SetVertTickPosition('plotleft');
  $graph->SetDataValues($stats);
  $graph->SetDataColors(array('blue','red'),array('blue', 'red'));

  $graph->DrawGraph();

  $graph->PrintImage();
?>
