<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2022 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

?>
    <style>
        #chart {
      max-width: 650px;
      margin: 35px auto;
    }
      
    </style>



     <div id="chart"></div>
	 
    <script>
         var options = {
          series: [{
          data: series.monthDataSeries1.prices
        }],
          chart: {	  
          height: 350,
          type: 'line',
          id: 'areachart-2'
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'straight'
        },
        grid: {
          padding: {
            right: 30,
            left: 20
          }
        },
        title: {
          text: 'Line with Annotations',
          align: 'left'
        },
        labels: series.monthDataSeries1.dates,
        xaxis: {
          type: 'datetime',
        },		
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
      
      
    </script>