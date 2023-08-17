<?php
/** ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: stats_sales_report2.php,v 1.00 2003/03/08 19:02:22
   ----------------------------------------------------------------------
   Charly Wilhelm  charly@yoshi.ch

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------

  possible views (srView):
  1 yearly
  2 monthly
  3 weekly
  4 daily

  possible options (srDetail):
  0 no detail
  1 show details (products)
  2 show details only (products)

  export
  0 normal view
  1 html view without left and right
  2 csv

  sort
  0 no sorting
  1 product description asc
  2 product description desc
  3 #product asc, product descr asc
  4 #product desc, product descr desc
  5 revenue asc, product descr asc
  6 revenue desc, product descr desc

   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/main.php';

  use Carbon\Carbon;

  Carbon::setLocale(LANG);

  require 'includes/classes/class_currencies.php';
  $currencies = new currencies();

  // default detail no detail
  $srDefaultDetail = 0;
  // default view (daily)
  $srDefaultView = 2;
  // default export
  $srDefaultExp = 0;
  // default sort
  $srDefaultSort = 4;

  // report views (1: yearly 2: monthly 3: weekly 4: daily)
if (isset($_GET['report']) && is_numeric($_GET['report'])) {
    $srView = intval($_GET['report']);
}
if (!isset($srView) || $srView < 1 || $srView > 4) {
    $srView = $srDefaultView;
}

  // detail
if (isset($_GET['detail']) && is_numeric($_GET['detail'])) {
    $srDetail = intval($_GET['detail']);
}
if (!isset($srDetail) || $srDetail < 0 || $srDetail > 2) {
    $srDetail = $srDefaultDetail;
}

  // report views (1: yearly 2: monthly 3: weekly 4: daily)
if (isset($_GET['export']) && is_numeric($_GET['export'])) {
    $srExp = intval($_GET['export']);
}
if (!isset($srExp) || $srExp < 0 || $srExp > 2) {
    $srExp = $srDefaultExp;
}

  // item_level
if (isset($_GET['max']) && is_numeric($_GET['max'])) {
    $srMax = intval($_GET['max']);
}
if (!isset($srMax)) {
    $srMax = 0;
}

  // order status
if (isset($_GET['status']) && is_numeric($_GET['status'])) {
    $srStatus = intval($_GET['status']);
}
if (!isset($srStatus)) {
    $srStatus = 0;
}

  // sort
if (isset($_GET['sort']) && is_numeric($_GET['sort'])) {
    $srSort = $_GET['sort'];
}
if (!isset($srSort) ||  $srSort < 1 || $srSort > 6) {
    $srSort = $srDefaultSort;
}

  // check start and end Date
  $startDate = "";
  $startDateG = 0;
if (isset($_GET['startD']) && oos_is_not_null($_GET['startD'])) {
    $sDay = $_GET['startD'];
    $startDateG = 1;
} else {
    $sDay = 1;
}
if (isset($_GET['startM']) && oos_is_not_null($_GET['startM'])) {
    $sMon = $_GET['startM'];
    $startDateG = 1;
} else {
    $sMon = 1;
}
if (isset($_GET['startY']) && oos_is_not_null($_GET['startY'])) {
    $sYear = $_GET['startY'];
    $startDateG = 1;
} else {
    $sYear = date("Y");
}
if ($startDateG) {
    $startDate = mktime(0, 0, 0, $sMon, $sDay, $sYear);
} else {
    $startDate = mktime(0, 0, 0, date("m"), 1, date("Y"));
}

  $endDate = "";
  $endDateG = 0;
if (isset($_GET['endD']) && oos_is_not_null($_GET['endD'])) {
    $eDay = $_GET['endD'];
    $endDateG = 1;
} else {
    $eDay = 1;
}
if (isset($_GET['endM']) && oos_is_not_null($_GET['endM'])) {
    $eMon = $_GET['endM'];
    $endDateG = 1;
} else {
    $eMon = 1;
}
if (isset($_GET['endY']) && oos_is_not_null($_GET['endY'])) {
    $eYear = $_GET['endY'];
    $endDateG = 1;
} else {
    $eYear = date("Y");
}
if ($endDateG) {
    $endDate = mktime(0, 0, 0, $eMon, $eDay + 1, $eYear);
} else {
    $endDate = mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"));
}

  require 'includes/classes/class_sales_report2.php';
  $sr = new sales_report($srView, $startDate, $endDate, $srSort, $srStatus);
  $startDate = $sr->startDate;
  $endDate = $sr->endDate;

if ($srExp < 2) {
    // not for csv export
    include 'includes/header.php';
    /*
          if ($srExp < 1) {
              require 'includes/header.php';
          }
    */ ?>
    <?php
    if ($srExp < 1) {
        ?>
<div class="wrapper">
    <!-- Header //-->
    <header class="topnavbar-wrapper">
        <!-- Top Navbar //-->
        <?php include 'includes/menue.php'; ?>
    </header>
    <!-- END Header //-->
    <aside class="aside">
        <!-- Sidebar //-->
        <div class="aside-inner">
        <?php include 'includes/blocks.php'; ?>
        </div>
        <!-- END Sidebar (left) //-->
    </aside>

    <!-- Main section //-->
    <section>
        <!-- Page content //-->
        <div class="content-wrapper">

<!-- body_text //-->
        <?php
    } // end sr_exp?>
            <!-- Breadcrumbs //-->
            <div class="content-heading">
                <div class="col-lg-12">
                    <h2><?php echo HEADING_TITLE; ?></h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
                        </li>
                        <li class="breadcrumb-item">
                            <?php echo '<a href="' . oos_href_link_admin($aContents['stats_products_purchased'], 'selected_box=reports') . '">' . BOX_HEADING_REPORTS . '</a>'; ?>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong><?php echo HEADING_TITLE; ?></strong>
                        </li>
                    </ol>
                </div>
            </div>
            <!-- END Breadcrumbs //-->

            <div class="wrapper wrapper-content">
                <div class="row">
                    <div class="col-lg-12">    


<!-- body_text //-->                
<div class="table-responsive">
    <?php
    if ($srExp < 1) {
        ?>
            <form action="" method="get">
              <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="left" rowspan="2" class="menuBoxHeading">
                    <input type="radio" name="report" value="1" <?php if ($srView == 1) {
            echo "checked";
        } ?>><?php echo REPORT_TYPE_YEARLY; ?><br>
                    <input type="radio" name="report" value="2" <?php if ($srView == 2) {
            echo "checked";
        } ?>><?php echo REPORT_TYPE_MONTHLY; ?><br>
                    <input type="radio" name="report" value="3" <?php if ($srView == 3) {
            echo "checked";
        } ?>><?php echo REPORT_TYPE_WEEKLY; ?><br>
                    <input type="radio" name="report" value="4" <?php if ($srView == 4) {
            echo "checked";
        } ?>><?php echo REPORT_TYPE_DAILY; ?><br>
                  </td>
                  <td class="menuBoxHeading">
        <?php echo REPORT_START_DATE; ?><br>
                    <select name="startD" size="1">
        <?php
        if ($startDate) {
            $j = date("j", $startDate);
        } else {
            $j = 1;
        }
        for ($i = 1; $i < 32; $i++) {
            ?>
                        <option<?php if ($j == $i) {
                echo ' selected="selected"';
            } ?>><?php echo $i; ?></option>
            <?php
        } ?>
                    </select>
                    <select name="startM" size="1">
        <?php
        if ($startDate) {
            $m = date("n", $startDate);
        } else {
            $m = 1;
        }
        for ($i = 1; $i < 13; $i++) {
            $monthName = (new Carbon())->setMonth($i)->isoFormat('MMMM'); ?>
                      <option<?php if ($m == $i) {
                echo ' selected="selected"';
            } ?> value="<?php echo $i; ?>"><?php echo $monthName; ?></option>
            <?php
        } ?>
                    </select>
                    <select name="startY" size="1">
        <?php
        if ($startDate) {
            $y = date("Y") - date("Y", $startDate);
        } else {
            $y = 0;
        }
        for ($i = 10; $i >= 0; $i--) {
            ?>
                      <option<?php if ($y == $i) {
                echo ' selected="selected"';
            } ?>><?php echo date("Y") - $i; ?></option>
            <?php
        } ?>
                    </select>
                  </td>
                  <td rowspan="2" align="left" class="menuBoxHeading">
                    <?php echo REPORT_DETAIL; ?><br>
                    <select name="detail" size="1">
                      <option value="0"<?php if ($srDetail == 0) {
            echo ' selected="selected"';
        } ?>><?php echo DET_HEAD_ONLY; ?></option>
                      <option value="1"<?php if ($srDetail == 1) {
            echo ' selected="selected"';
        } ?>><?php echo DET_DETAIL; ?></option>
                      <option value="2"<?php if ($srDetail == 2) {
            echo ' selected="selected"';
        } ?>><?php echo DET_DETAIL_ONLY; ?></option>
                    </select><br>
        <?php echo REPORT_MAX; ?><br>
                    <select name="max" size="1">
                      <option value="0"><?php echo REPORT_ALL; ?></option>
                      <option<?php if ($srMax == 1) {
            echo ' selected="selected"';
        } ?>>1</option>
                      <option<?php if ($srMax == 3) {
            echo ' selected="selected"';
        } ?>>3</option>
                      <option<?php if ($srMax == 5) {
            echo ' selected="selected"';
        } ?>>5</option>
                      <option<?php if ($srMax == 10) {
            echo ' selected="selected"';
        } ?>>10</option>
                      <option<?php if ($srMax == 25) {
            echo ' selected="selected"';
        } ?>>25</option>
                      <option<?php if ($srMax == 50) {
            echo ' selected="selected"';
        } ?>>50</option>
                    </select>
                  </td>
                  <td rowspan="2" align="left" class="menuBoxHeading">
                    <?php echo REPORT_STATUS_FILTER; ?><br>
                    <select name="status" size="1">
                      <option value="0"><?php echo REPORT_ALL; ?></option>
        <?php
        foreach ($sr->status as $value) {
            ?>
                      <option value="<?php echo $value["orders_status_id"]?>"<?php if ($srStatus == $value["orders_status_id"]) {
                echo ' selected="selected"';
            } ?>><?php echo $value["orders_status_name"] ; ?></option>
            <?php
        } ?>
                    </select><br>
                  </td>
                  <td rowspan="2" align="left" class="menuBoxHeading">
                    <?php echo REPORT_EXP; ?><br>
                    <select name="export" size="1">
                      <option value="0" selected="selected"><?php echo EXP_NORMAL; ?></option>
                      <option value="1"><?php echo EXP_HTML; ?></option>
                      <option value="2"><?php echo EXP_CSV; ?></option>
                    </select><br>
                    <?php echo REPORT_SORT; ?><br>
                    <select name="sort" size="1">
                      <option value="0"<?php if ($srSort == 0) {
            echo ' selected="selected"';
        } ?>><?php echo SORT_VAL0; ?></option>
                      <option value="1"<?php if ($srSort == 1) {
            echo ' selected="selected"';
        } ?>><?php echo SORT_VAL1; ?></option>
                      <option value="2"<?php if ($srSort == 2) {
            echo ' selected="selected"';
        } ?>><?php echo SORT_VAL2; ?></option>
                      <option value="3"<?php if ($srSort == 3) {
            echo ' selected="selected"';
        } ?>><?php echo SORT_VAL3; ?></option>
                      <option value="4"<?php if ($srSort == 4) {
            echo ' selected="selected"';
        } ?>><?php echo SORT_VAL4; ?></option>
                      <option value="5"<?php if ($srSort == 5) {
            echo ' selected="selected"';
        } ?>><?php echo SORT_VAL5; ?></option>
                      <option value="6"<?php if ($srSort == 6) {
            echo ' selected="selected"';
        } ?>><?php echo SORT_VAL6; ?></option>
                    </select><br>
                  </td>
                </tr>
                <tr>
                  <td class="menuBoxHeading">
        <?php echo REPORT_END_DATE; ?><br>
                    <select name="endD" size="1">
        <?php
        if ($endDate) {
            $j = date("j", $endDate - 60* 60 * 24);
        } else {
            $j = date("j");
        }
        for ($i = 1; $i < 32; $i++) {
            ?>
                      <option<?php if ($j == $i) {
                echo ' selected="selected"';
            } ?>><?php echo $i; ?></option>
            <?php
        } ?>
                    </select>
                    <select name="endM" size="1">
        <?php
        if ($endDate) {
            $m = date("n", $endDate - 60* 60 * 24);
        } else {
            $m = date("n");
        }
        for ($i = 1; $i < 13; $i++) {
            $monthName = (new Carbon())->setMonth($i)->isoFormat('MMMM'); ?>
                      <option<?php if ($m == $i) {
                echo ' selected="selected"';
            } ?> value="<?php echo $i; ?>"><?php echo $monthName; ?></option>
            <?php
        } ?>
                    </select>
                    <select name="endY" size="1">
        <?php
        if ($endDate) {
            $y = date("Y") - date("Y", $endDate - 60* 60 * 24);
        } else {
            $y = 0;
        }
        for ($i = 10; $i >= 0; $i--) {
            ?>
                      <option<?php if ($y == $i) {
                echo ' selected="selected"';
            } ?>><?php echo date("Y") - $i; ?></option>
            <?php
        } ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td colspan="5" class="menuBoxHeading" align="right">
                    <input type="submit" value="<?php echo REPORT_SEND; ?>">
                  </td>
              </table>
            </form>
        <?php
    } // end of ($srExp < 1)
    ?>

        <table class="table w-100">
              <tr>
                <td valign="top">

                <table class="table table-striped table-hover w-100">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-right"><?php echo TABLE_HEADING_DATE; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_ORDERS; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_ITEMS; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_REVENUE; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_SHIPPING; ?></th>
                        </tr>    
                    </thead>
<?php
} // end of if $srExp < 2 csv export
$sum = 0;
while ($sr->actDate < $sr->endDate) {
    $info = $sr->getNext();
    $last = count($info) - 1;
    if ($srExp < 2) {
        ?>
                    <tr>
        <?php
        switch ($srView) {
        case '3':
            ?>
                      <td class="text-right"><?php echo oos_date_long(date("Y-m-d\ H:i:s", $sr->showDate)) . " - " . oos_date_short(date("Y-m-d\ H:i:s", $sr->showDateEnd)); ?></td>
            <?php
            break;
        case '4':
            ?>
                      <td class="text-right"><?php echo oos_date_long(date("Y-m-d\ H:i:s", $sr->showDate)); ?></td>
            <?php
            break;
        default:
            ?>
                      <td class="text-right"><?php echo oos_date_short(date("Y-m-d\ H:i:s", $sr->showDate)) . " - " . oos_date_short(date("Y-m-d\ H:i:s", $sr->showDateEnd)); ?></td>
            <?php
        } ?>
                      <td class="text-right"><?php echo(isset($info[0]['order']) ? $info[0]['order'] : ''); ?></td>
                      <td class="text-right"><?php echo(isset($info[$last - 1]['totitem']) ? $info[$last - 1]['totitem'] : ''); ?></td>
                      <td class="text-right"><?php echo $currencies->format((isset($info[$last - 1]['totsum']) ? $info[$last - 1]['totsum'] : 0)); ?></td>
                      <td class="text-right"><?php echo $currencies->format((isset($info[0]['shipping']) ? $info[0]['shipping'] : 0)); ?></td>
                    </tr>
        <?php
    } else {
        // csv export
        echo date(DATE_FORMAT, $sr->showDate) . SR_SEPARATOR1 . date(DATE_FORMAT, $sr->showDateEnd) . SR_SEPARATOR1;
        echo $info[0]['order'] . SR_SEPARATOR1;
        echo $info[$last - 1]['totitem'] . SR_SEPARATOR1;
        echo $currencies->format($info[$last - 1]['totsum']) . SR_SEPARATOR1;
        echo $currencies->format($info[0]['shipping']) . SR_NEWLINE;
    }
    if ($srDetail) {
        for ($i = 0; $i < $last; $i++) {
            if ($srMax == 0 or $i < $srMax) {
                if ($srExp < 2) {
                    ?>
                    <tr>
                    <td>&nbsp;</td>
                    <td class="text-left"><a href="<?php echo oos_catalog_link($aCatalog['product_info'], 'products_id=' . $info[$i]['pid']) ?>" target="_blank" rel="noopener"><?php echo $info[$i]['pname']; ?></a>
                    <?php
                    if (is_array($info[$i]['attr'])) {
                        $attr_info = $info[$i]['attr'];
                        foreach ($attr_info as $attr) {
                            echo '<div style="font-style:italic;">&nbsp;' . $attr['quant'] . 'x ' ;
                            //  $attr['options'] . ': '
                            $flag = 0;
                            foreach ($attr['options_values'] as $value) {
                                if ($flag > 0) {
                                    echo "," . $value;
                                } else {
                                    echo $value;
                                    $flag = 1;
                                }
                            }
                            $price = 0;
                            foreach ($attr['price'] as $value) {
                                $price += $value;
                            }
                            if ($price != 0) {
                                echo ' (';
                                if ($price > 0) {
                                    echo "+";
                                }
                                echo $currencies->format($price). ')';
                            }
                            echo '</div>';
                        }
                    } ?>                    </td>
                    <td class="text-right"><?php echo $info[$i]['pquant']; ?></td>
                    <?php
                    if ($srDetail == 2) {?>
                    <td class="text-right"><?php echo $currencies->format($info[$i]['psum']); ?></td>
                        <?php
                    } else { ?>
                    <td>&nbsp;</td>
                        <?php
                    } ?>
                    <td>&nbsp;</td>
                  </tr>
                    <?php
                } else {
                    // csv export
                    if (is_array($info[$i]['attr'])) {
                        $attr_info = $info[$i]['attr'];
                        foreach ($attr_info as $attr) {
                            echo $info[$i]['pname'] . "(";
                            $flag = 0;
                            foreach ($attr['options_values'] as $value) {
                                if ($flag > 0) {
                                    echo ", " . $value;
                                } else {
                                    echo $value;
                                    $flag = 1;
                                }
                            }
                            $price = 0;
                            foreach ($attr['price'] as $value) {
                                $price += $value;
                            }
                            if ($price != 0) {
                                echo ' (';
                                if ($price > 0) {
                                    echo "+";
                                } else {
                                    echo " ";
                                }
                                echo $currencies->format($price). ')';
                            }
                            echo ")" . SR_SEPARATOR2;
                            if ($srDetail == 2) {
                                echo $attr['quant'] . SR_SEPARATOR2;
                                echo $currencies->format($attr['quant'] * ($info[$i]['price'] + $price)) . SR_NEWLINE;
                            } else {
                                echo $attr['quant'] . SR_NEWLINE;
                            }
                            $info[$i]['pquant'] = $info[$i]['pquant'] - $attr['quant'];
                        }
                    }
                    if ($info[$i]['pquant'] > 0) {
                        echo $info[$i]['pname'] . SR_SEPARATOR2;
                        if ($srDetail == 2) {
                            echo $info[$i]['pquant'] . SR_SEPARATOR2;
                            echo $currencies->format($info[$i]['pquant'] * $info[$i]['price']) . SR_NEWLINE;
                        } else {
                            echo $info[$i]['pquant'] . SR_NEWLINE;
                        }
                    }
                }
            }
        }
    }
}
if ($srExp < 2) {
    ?>
                  </table>
                </td>
              </tr>
            </table>
        </div>
<!-- body_text_eof //-->
                </div>
            </div>
        </div>

        </div>
    </section>
    <!-- Page footer //-->
    <footer>
        <span>&copy; <?php echo date('Y'); ?> - <a href="https://www.oos-shop.de" target="_blank" rel="noopener">MyOOS [Shopsystem]</a></span>
    </footer>
</div>


    <?php
    include 'includes/bottom.php';
    include 'includes/nice_exit.php';
}
?>