<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: logoff.php,v 1.12 2003/02/13 03:01:51 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/main.php';

  unset($_SESSION['login_id']);
  unset($_SESSION['login_firstname']);
  unset($_SESSION['login_groups_id']);

require 'includes/header.php';
?>
    <div class="wrapper wrapper-content">


        <div class="login">

            <!-- begin login-content -->
            <div class="login-content">
                    
            
                <div class="login-header text-center">
                    <i class="mdi mdi-radar"></i> <span>MyOOS [Shopsystem] </span>
                </div>

                <div class="alert alert-danger alert-dismissible fade show m-b-20" role="alert">
                    <?php echo TEXT_MAIN; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>    

                <div class="form-group text-right m-t-20">
                    <div class="col-xs-12">
                        <?php echo '<a href="' . oos_href_link_admin($aContents['login']) . '">'; ?><button class="btn btn-primary btn-custom w-md" type="submit"><?php echo BUTTON_BACK; ?></button></a>
                    </div>
                </div>    
                
            </div>
        </div>
        
    </div>

<?php
    require 'includes/bottom.php';
    require 'includes/nice_exit.php';
?>