<?php
/* ----------------------------------------------------------------------
   $Id: vcard.php,v 1.1 2007/06/07 16:45:18 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: contact.php,v 1.10 2005/02/16 13:37:39 stingrey
   ----------------------------------------------------------------------
   Mambo is Free Software
   http://www.mamboserver.com/

   Copyright (c) 2000 - 2005 Miro International Pty Ltd
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  require 'includes/languages/' . $sLanguage . '/info_vcard.php';
  require 'includes/classes/class_vcard.php';


  $name  = explode( ' ', STORE_OWNER );
  $count = count( $name );

  $surname  = '';
  $middlename  = '';

  switch( $count ) {
    case 1:
      $firstname    = $name[0];
      break;

    case 2:
      $firstname    = $name[0];
      $surname      = $name[1];
      break;

    default:
      $firstname    = $name[0];
      $surname      = $name[$count-1];
      for ( $i = 1; $i < $count - 1 ; $i++ ) {
        $middlename  .= $name[$i] .' ';
      }
      break;
  }
  $middlename  = trim( $middlename );


  $v = new oosvCard();

  $v->setPhoneNumber( TEXT_VCARD_PHONE_NUMBER_HOME, "PREF;HOME;VOICE" );
  $v->setPhoneNumber( TEXT_VCARD_FAX_NUMBER_HOME, 'HOME;FAX' );
  $v->setAddress('', '',  TEXT_VCARD_STREET_HOME, TEXT_VCARD_CITY_HOME, TEXT_VCARD_REGION_HOME, TEXT_VCARD_ZIP_HOME, TEXT_VCARD_COUNTRY_HOME, 'HOME' );
  $v->setBirthday( TEXT_VCARD_BIRTHDAY );
  $v->setName( $surname, $firstname, $middlename, '' );

  $v->setPhoneNumber( TEXT_VCARD_PHONE_NUMBER_WORK, "PREF;WORK;VOICE" );
  $v->setPhoneNumber( TEXT_VCARD_FAX_NUMBER_HOME, 'WORK;FAX' );
  $v->setAddress('', '',  TEXT_VCARD_STREET_WORK, TEXT_VCARD_CITY_WORK, TEXT_VCARD_REGION_WORK, TEXT_VCARD_ZIP_WORK, TEXT_VCARD_COUNTRY_WORK, 'WORK' );
  $v->setEmail( STORE_OWNER_EMAIL_ADDRESS );
  $v->setNote( TEXT_VCARD_NOTE );
  $v->setURL( OOS_HTTP_SERVER . OOS_SHOP, "WORK");
  $v->setTitle( TEXT_VCARD_TITLE );
  $v->setOrg( STORE_NAME );

  $filename  = str_replace( ' ', '_', STORE_NAME );
  $v->setFilename( $filename );

  $output   = $v->getVCard( STORE_OWNER );
  $filename = $v->getFileName();


  // header info for page
  header( 'Content-Disposition: attachment; filename='. $filename );
  header( 'Content-Length: '. strlen( $output ) );
  header( 'Connection: close' );
  header( 'Content-Type: text/x-vCard; name='. $filename );

  print $output;
?>
