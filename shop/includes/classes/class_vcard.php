<?php
/* ----------------------------------------------------------------------
   $Id: class_vcard.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File:  contact.class.php,v 1.6 2005/01/23 08:28:14 stingrey 
   ----------------------------------------------------------------------
   Mambo is Free Software
   http://www.mamboserver.com/

   Copyright (c) 2000 - 2005 Miro International Pty Ltd
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );


  include_once MYOOS_INCLUDE_PATH . '/includes/classes/vcard/vcard.php';


  /**
   * * class needed to extend vcard class and to correct minor errors
   */
  class oosvCard extends vCard {

    // needed to fix bug in vcard class
    function setName( $family='', $first='', $additional='', $prefix='', $suffix='' ) {
      $this->properties["N"]   = "$family;$first;$additional;$prefix;$suffix";
      $this->setFormattedName( trim( "$prefix $first $additional $family $suffix" ) );
    }

    // needed to fix bug in vcard class
    function setAddress( $postoffice='', $extended='', $street='', $city='', $region='', $zip='', $country='', $type='HOME;POSTAL' ) {
      // $type may be DOM | INTL | POSTAL | PARCEL | HOME | WORK or any combination of these: e.g. "WORK;PARCEL;POSTAL"
      $key   = 'ADR';
      if ( $type != '' ) {
        $key  .= ';'. $type;
      }
      $key.= ';ENCODING=QUOTED-PRINTABLE';
      $this->properties[$key] = encode( $postoffice ) . ';' . encode( $extended ) .';'. encode( $street ) .';'. encode( $city ) .';'. encode( $region) .';'. encode( $zip ) .';'. encode( $country );
    }

    // added ability to set filename
    function setFilename( $filename ) {
      $this->filename = $filename .'.vcf';
    }

    // added ability to set position/title
    function setTitle( $title ) {
      $title   = trim( $title );
      $this->properties['TITLE']   = $title;
    }
  
    // added ability to set organisation/company
    function setOrg( $org ) {
      $org   = trim( $org );
      $this->properties['ORG']   = $org;
    }

    function getVCard( $sitename ) {
      $text   = "BEGIN:VCARD\r\n";
      $text   .= "VERSION:2.1\r\n";
      foreach( $this->properties as $key => $value ) {
        $text  .= "$key:$value\r\n";
      }
      $text  .= "REV:" .date("Y-m-d") ."T". date("H:i:s"). "Z\r\n";
      $text  .= "MAILER: OOS vCard for ". $sitename ."\r\n";
      $text  .= "END:VCARD\r\n";
      return $text;
    }
  
  }

?>
