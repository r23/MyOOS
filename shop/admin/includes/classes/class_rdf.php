<?php
/* ----------------------------------------------------------------------
   $Id: class_rdf.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  /**
   * RSS Parser
   *
   * This class offers methods to parse RSS Files
   *
   * {@link http://www.fase4.com/rdf/ Latest release of this class}
   */
   require '../includes/classes/fase4/rdf.class.php' ;

  /**
   * RSS Parser
   *
   * @package rss
   * @version $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/08 14:58:10 $
   */
   class oosRDF extends fase4_rdf {

    /**
     * Constructor
     */
     function oosRDF() {

       $this->fase4_rdf();

       $this->set_cachedir( OOS_TEMP_PATH . 'rss_cache/' );
       $this->set_table_width( "98%" );
       $this->_link_target = "_blank";

     }
   }

?>