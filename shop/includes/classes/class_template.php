<?php
/* ----------------------------------------------------------------------
   $Id: class_template.php,v 1.1 2007/06/07 16:06:31 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  /**
   * Smarty Template System
   *
   * {@link http://smarty.php.net/ smarty.php.net}
   * {@link http://smarty.incutio.com/ smarty wiki}
   * {@link http://marc.theaimsgroup.com/?l=smarty-general&r=1&w=2 mail list archive}
   */
   include SMARTY_DIR . 'Smarty.class.php';
   include SMARTY_DIR . 'SmartyValidate.class.php';

  /**
   * Template engine
   *
   * @package  Smarty
   */
   class Template extends Smarty  {

    /**
     * Constructor
     */
     function Template() {

       $this->Smarty();

       $this->left_delimiter =  '{';
       $this->right_delimiter =  '}';

       $dir = OOS_TEMP_PATH;
       if (substr($dir, -1) != "/") {
         $dir = $dir."/";
       }

       $this->template_dir = $dir . 'shop/templates/';
       $this->compile_dir = $dir . 'shop/templates_c/';
       $this->config_dir = $dir . 'shop/configs/';
       $this->cache_dir = $dir . 'shop/cache/';

       array_push($this->plugins_dir, SMARTY_DIR . '/plugins');
       array_push($this->plugins_dir, 'includes/plugins/thirdparty/smarty');

       $this->use_sub_dirs = false;

       $thstamp  = mktime(0, 0, 0, date ("m") , date ("d")+80, date("Y"));
       $oos_date = date("D,d M Y", $thstamp);

       $this->assign(
           array(
               'oos_revision_date' => $oos_date,
               'oos_date_long'     => strftime(DATE_FORMAT_LONG)
           )
       );

     }
   }


  /**
   * @param $tpl_cource
   * @param $smarty
   */
   function oosAddHeaderComment($tpl_source, &$smarty) {
     return "<?php echo \"<!-- Created by Smarty! -->\n\" ?>\n".$tpl_source;
   }

?>