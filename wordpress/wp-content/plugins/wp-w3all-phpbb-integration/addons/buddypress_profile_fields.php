<?php defined( 'ABSPATH' ) or die( 'forbidden' );

// BUDDYPRESS profile integration  
	
   global $w3_bpl_profile_occupation, $w3_bpl_profile_location, $w3_bpl_profile_interests, $w3_bpl_profile_website;
   
   // i've not find out any way to get BP profile data for the user at this point using Buddypress core functions ... 
   // if anybody know how to get these data for the user, without the following query, would be really great!
   // i've not follow check and this have not help: https://codex.buddypress.org/developer/loops-reference/the-profile-fields-loop-bp_has_profile/  
   // thus until no light about, next two WP queries ...
   
   // Any help on improve this would be very appreciated!
   // Should be done by ID, but how without resetting existing installations configurations about profile fields?
   // May it was less complicate, in certain conditions, but not suitable for all. 
   // So here the joke about DELETE or UPDATE or INSERT, and full integration of BP profile fields in phpBB profile fields, where fields names match as explained on procedure:
   //https://www.axew3.com/w3/2017/09/wordpress-and-buddypress-phpbb-profile-fields-integration/
   
   $bp_uf = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."bp_xprofile_data, ".$wpdb->prefix ."bp_xprofile_fields, ".$wpdb->prefix ."usermeta 
     WHERE ".$wpdb->prefix."bp_xprofile_data.user_id = $current_user->ID 
    AND ".$wpdb->prefix."bp_xprofile_data.field_id = ".$wpdb->prefix."bp_xprofile_fields.id 
    AND ".$wpdb->prefix."usermeta.user_id = ".$wpdb->prefix."bp_xprofile_data.user_id  
    AND ".$wpdb->prefix ."usermeta.meta_key = 'bp_xprofile_visibility_levels'");

   $bp_f = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."bp_groups_members, ".$wpdb->prefix."bp_xprofile_fields
     WHERE ".$wpdb->prefix."bp_groups_members.user_id = $current_user->ID
    AND ".$wpdb->prefix."bp_xprofile_fields.group_id = ".$wpdb->prefix."bp_groups_members.group_id");
 
   $db_bp_pf_data = $wpdb->prefix . 'bp_xprofile_data'; 

  if(!empty($bp_uf)){

   // any empty phpBB field that match the name field in BP, will be updated if not empty in phpBB
   // if empty in phpBB, will be deleted (as BP do) in BP xprofile_data table

   	foreach( $bp_uf as $uu => $ff ):
   	
   	// remove from this array containing all BP fields of this user, all values that have been passed on this foreach
   	// so we'll have values to INSERT, in case, if not UPDATED on the follow: no UPDATE, may because the field could be not existent on table: it is removed by BP on xprofile_data table (on update action) when a field is empty)
   	
   	  foreach( $bp_f as $u => $f ):
   	   if($ff->field_id == $f->id){
   	  	 unset($bp_f[$u]);
   	  	}
   	  
   	  endforeach;
 
 // UPDATE 'existent' WP recognized fields AND grab what need to be deleted (because empty field in phpBB)
     if ( stripos($ff->name, 'youtube' ) && $ff->value != $phpbb_user_session[0]->pf_phpbb_youtube ){
      if( !empty($phpbb_user_session[0]->pf_phpbb_youtube) ){
        $youtube_up = "WHEN '".$ff->field_id."' THEN '".$phpbb_user_session[0]->pf_phpbb_youtube."'";
        $do_up = true;
       } else { $del = true; $del_youtube = $ff->field_id; }
       	
      } elseif ( stripos($ff->name, 'google' ) && $ff->value != $phpbb_user_session[0]->pf_phpbb_googleplus ){
      if( !empty($phpbb_user_session[0]->pf_phpbb_googleplus) ){
        $googleplus_up = "WHEN '".$ff->field_id."' THEN '".$phpbb_user_session[0]->pf_phpbb_googleplus."'";
        $do_up = true;
      } else { $del = true; $del_googleplus = $ff->field_id; }
      	
      } elseif  ( stripos($ff->name, 'skype' ) && $ff->value != $phpbb_user_session[0]->pf_phpbb_skype ){
      if( !empty($phpbb_user_session[0]->pf_phpbb_skype) ){
        $skype_up = "WHEN '".$ff->field_id."' THEN '".$phpbb_user_session[0]->pf_phpbb_skype."'";
        $do_up = true;
       } else { $del = true; $del_skype = $ff->field_id; }
      	
      } elseif  ( stripos($ff->name, 'twitter' ) && $ff->value != $phpbb_user_session[0]->pf_phpbb_twitter ){
      if( !empty($phpbb_user_session[0]->pf_phpbb_twitter) ){
        $twitter_up = "WHEN '".$ff->field_id."' THEN '".$phpbb_user_session[0]->pf_phpbb_twitter."'";
        $do_up = true;
       } else { $del = true; $del_twitter = $ff->field_id; }
       	
      } elseif ( stripos($ff->name, 'facebook' ) && $ff->value != $phpbb_user_session[0]->pf_phpbb_facebook ){
      if( !empty($phpbb_user_session[0]->pf_phpbb_facebook) ){
       $facebook_up = "WHEN '".$ff->field_id."' THEN '".$phpbb_user_session[0]->pf_phpbb_facebook."'";
       $do_up = true;
       } else { $del = true; $del_facebook = $ff->field_id; }
       	
      } elseif ( stripos($ff->name, 'yahoo' ) && $ff->value != $phpbb_user_session[0]->pf_phpbb_yahoo ){
      if( !empty($phpbb_user_session[0]->pf_phpbb_yahoo) ){
       $yahoo_up = "WHEN '".$ff->field_id."' THEN '".$phpbb_user_session[0]->pf_phpbb_yahoo."'";
       $do_up = true;
      } else { $del = true; $del_yahoo = $ff->field_id; }
      
      } elseif ( stripos($ff->name, 'icq' ) && $ff->value != $phpbb_user_session[0]->pf_phpbb_icq ){
      if( !empty($phpbb_user_session[0]->pf_phpbb_icq) ){
       $icq_up = "WHEN '".$ff->field_id."' THEN '".$phpbb_user_session[0]->pf_phpbb_icq."'";
       $do_up = true;
      } else { $del = true; $del_icq = $ff->field_id; }
       
      } elseif ( stripos($ff->name, 'aol' ) && $ff->value != $phpbb_user_session[0]->pf_phpbb_aol ){
      if( !empty($phpbb_user_session[0]->pf_phpbb_aol) ){
       $aol_up = "WHEN '".$ff->field_id."' THEN '".$phpbb_user_session[0]->pf_phpbb_aol."'";
       $do_up = true;
      } else { $del = true; $del_aol = $ff->field_id; }
      
      } elseif ( array_search(trim(strtolower($ff->name)), $w3_bpl_profile_interests ) && $phpbb_user_session[0]->pf_phpbb_interests != $ff->value ){
      if( !empty($phpbb_user_session[0]->pf_phpbb_interests) ){
       $interests_up = "WHEN '".$ff->field_id."' THEN '".$phpbb_user_session[0]->pf_phpbb_interests."'";
       $do_up = true;
      } else { $del = true; $del_interests = $ff->field_id; }
      	
      } elseif ( array_search(trim(strtolower($ff->name)), $w3_bpl_profile_occupation ) && $phpbb_user_session[0]->pf_phpbb_occupation != $ff->value ){
      if( !empty($phpbb_user_session[0]->pf_phpbb_occupation) ){
       $occupation_up = "WHEN '".$ff->field_id."' THEN '".$phpbb_user_session[0]->pf_phpbb_occupation."'";
       $do_up = true;
      } else { $del = true; $del_occupation = $ff->field_id; }
      	
      } elseif ( array_search(trim(strtolower($ff->name)), $w3_bpl_profile_location ) && $phpbb_user_session[0]->pf_phpbb_location != $ff->value ){
      if( !empty($phpbb_user_session[0]->pf_phpbb_location) ){
       $location_up = "WHEN '".$ff->field_id."' THEN '".$phpbb_user_session[0]->pf_phpbb_location."'";
       $do_up = true;
      } else { $del = true; $del_location = $ff->field_id; }
      	
      } elseif ( array_search(trim(strtolower($ff->name)), $w3_bpl_profile_website ) && $phpbb_user_session[0]->pf_phpbb_website != $ff->value ){
      if( !empty($phpbb_user_session[0]->pf_phpbb_website) ){
       $website_up = "WHEN '".$ff->field_id."' THEN '".$phpbb_user_session[0]->pf_phpbb_website."'";
       $do_up = true;
      } else { $del = true; $del_website = $ff->field_id; }
      	 
      } else { // nothing at moment 
      	}  
   
   	endforeach;
   	
} // end if(!empty($uf)){

// update 
 if( isset($do_up) ){
         
  $youtube_up = isset($youtube_up) ? $youtube_up : '';
  $googleplus_up = isset($googleplus_up) ? $googleplus_up : '';
  $skype_up = isset($skype_up) ? $skype_up : '';
  $twitter_up = isset($twitter_up) ? $twitter_up : '';
  $facebook_up = isset($facebook_up) ? $facebook_up : '';
  $yahoo_up = isset($yahoo_up) ? $yahoo_up : '';
  $icq_up = isset($icq_up) ? $icq_up : '';
  $aol_up = isset($aol_up) ? $aol_up : '';
  $interests_up = isset($interests_up) ? $interests_up : '';
  $occupation_up = isset($occupation_up) ? $occupation_up : '';
  $location_up = isset($location_up) ? $location_up : '';
  $website_up = isset($website_up) ? $website_up : '';

 	  $wpdb->query("UPDATE $db_bp_pf_data SET value = CASE field_id $youtube_up $googleplus_up $skype_up $twitter_up $facebook_up $yahoo_up $icq_up $aol_up $interests_up $occupation_up $location_up $website_up 
   ELSE value END WHERE user_id = '$current_user->ID'"); 

 }
 
// DELETE emtpy recognized BP fields, if value is empty in phpBB
// delete
 if( isset($del) ){

  $del_youtube = isset($del_youtube) ? "'$del_youtube'," : "";
  $del_googleplus = isset($del_googleplus) ? "'$del_googleplus'," : "";
  $del_skype = isset($del_skype) ? "'$del_skype'," : "";
  $del_twitter = isset($del_twitter) ? "'$del_twitter'," : "";
  $del_facebook = isset($del_facebook) ? "'$del_facebook'," : "";
  $del_yahoo = isset($del_yahoo) ? "'$del_yahoo'," : "";
  $del_icq = isset($del_icq) ? "'$del_icq'," : "";
  $del_aol = isset($del_aol) ? "'$del_aol'," : "";
  $del_interests = isset($del_interests) ? "'$del_interests'," : "";
  $del_occupation = isset($del_occupation) ? "'$del_occupation'," : "";
  $del_location = isset($del_location) ? "'$del_location'," : "";
  $del_website = isset($del_website) ? "'$del_website'," : "";

  $bp_del_fields_ids = $del_youtube . $del_googleplus . $del_skype . $del_twitter . $del_facebook . $del_yahoo . $del_icq . $del_aol . $del_interests . $del_occupation . $del_location . $del_website;
  $bp_del_fields_ids = substr($bp_del_fields_ids, 0, -1);

  $wpdb->query("DELETE FROM $db_bp_pf_data WHERE field_id IN( ".$bp_del_fields_ids." ) AND user_id = '$current_user->ID'");

}
 
 // INSERT recognized fields, if still not existent in BP profile data table (so not UPDATED on previous query)
 // thus, the follow should build all user's fields to INSERT, minus all the UPDATED above 
 // and after used to INSERT, if the case ...

date_default_timezone_set('UTC');
$last_updated = date('Y-m-d H:i:s');

	foreach( $bp_f as $uu => $ff ):
	
	  if ( stripos($ff->name, 'youtube' ) !== false && !empty($phpbb_user_session[0]->pf_phpbb_youtube) ){
       $youtube_up = "( '', '$ff->id', '$current_user->ID', '".$phpbb_user_session[0]->pf_phpbb_youtube."', '$last_updated' ),";
       $do_ins = true;
      } elseif ( stripos($ff->name, 'google' ) !== false && !empty($phpbb_user_session[0]->pf_phpbb_googleplus) ){
       $googleplus_up = "( '', '$ff->id', '$current_user->ID', '".$phpbb_user_session[0]->pf_phpbb_googleplus."', '$last_updated' ),";
       $do_ins = true;
      } elseif  ( stripos($ff->name, 'skype' ) !== false && !empty($phpbb_user_session[0]->pf_phpbb_skype) ){
       $skype_up = "( '', '$ff->id', '$current_user->ID', '".$phpbb_user_session[0]->pf_phpbb_skype."', '$last_updated' ),";
       $do_ins = true;
      } elseif  ( stripos($ff->name, 'twitter' ) !== false && !empty($phpbb_user_session[0]->pf_phpbb_twitter) ){
        $twitter_up = "( '', '$ff->id', '$current_user->ID', '".$phpbb_user_session[0]->pf_phpbb_twitter."', '$last_updated' ),";
        $do_ins = true;
      } elseif ( stripos($ff->name, 'facebook' ) !== false && !empty($phpbb_user_session[0]->pf_phpbb_facebook) ){
       $facebook_up = "( '', '$ff->id', '$current_user->ID', '".$phpbb_user_session[0]->pf_phpbb_facebook."', '$last_updated' ),";
       $do_ins = true;
      } elseif ( stripos($ff->name, 'yahoo' ) !== false && !empty($phpbb_user_session[0]->pf_phpbb_yahoo) ){
       $yahoo_up = "( '', '$ff->id', '$current_user->ID', '".$phpbb_user_session[0]->pf_phpbb_yahoo."', '$last_updated' ),";
       $do_ins = true;
      } elseif ( stripos($ff->name, 'icq' ) !== false && !empty($phpbb_user_session[0]->pf_phpbb_icq) ){
       $icq_up = "( '', '$ff->id', '$current_user->ID', '".$phpbb_user_session[0]->pf_phpbb_icq."', '$last_updated' ),";
       $do_ins = true;
      } elseif ( stripos($ff->name, 'aol' ) !== false && !empty($phpbb_user_session[0]->pf_phpbb_aol) ){
       $aol_up = "( '', '$ff->id', '$current_user->ID', '".$phpbb_user_session[0]->pf_phpbb_aol."', '$last_updated' ),";
       $do_ins = true;
      } elseif ( array_search(trim(strtolower($ff->name)), $w3_bpl_profile_interests ) && !empty($phpbb_user_session[0]->pf_phpbb_interests) ){
       $interests_up = "( '', '$ff->id', '$current_user->ID', '".$phpbb_user_session[0]->pf_phpbb_interests."', '$last_updated' ),";
       $do_ins = true;
      } elseif ( array_search(trim(strtolower($ff->name)), $w3_bpl_profile_occupation ) && !empty($phpbb_user_session[0]->pf_phpbb_occupation) ){
       $occupation_up = "( '', '$ff->id', '$current_user->ID', '".$phpbb_user_session[0]->pf_phpbb_occupation."', '$last_updated' ),";
       $do_ins = true;
      } elseif ( array_search(trim(strtolower($ff->name)), $w3_bpl_profile_location ) && !empty($phpbb_user_session[0]->pf_phpbb_location) ){
       $location_up = "( '', '$ff->id', '$current_user->ID', '".$phpbb_user_session[0]->pf_phpbb_location."', '$last_updated' ),";
       $do_ins = true;
      } elseif ( array_search(trim(strtolower($ff->name)), $w3_bpl_profile_website ) && !empty($phpbb_user_session[0]->pf_phpbb_website) ){
       $website_up = "( '', '$ff->id', '$current_user->ID', '".$phpbb_user_session[0]->pf_phpbb_website."', '$last_updated' ),";
       $do_ins = true;
      } else { // nothing at moment 
      	}  
	
 endforeach;
	
	// which of those need to be inserted?
  $youtube_up = isset($youtube_up) ? $youtube_up : '';
  $googleplus_up = isset($googleplus_up) ? $googleplus_up : '';
  $skype_up = isset($skype_up) ? $skype_up : '';
  $twitter_up = isset($twitter_up) ? $twitter_up : '';
  $facebook_up = isset($facebook_up) ? $facebook_up : '';
  $yahoo_up = isset($yahoo_up) ? $yahoo_up : '';
  $icq_up = isset($icq_up) ? $icq_up : '';
  $aol_up = isset($aol_up) ? $aol_up : '';
  $interests_up = isset($interests_up) ? $interests_up : '';
  $occupation_up = isset($occupation_up) ? $occupation_up : '';
  $location_up = isset($location_up) ? $location_up : '';
  $website_up = isset($website_up) ? $website_up : '';	

// insert
 if(isset($do_ins)){
 	$insert_uf = $youtube_up . $googleplus_up . $skype_up . $twitter_up . $facebook_up . $yahoo_up . $icq_up . $aol_up . $interests_up . $occupation_up . $location_up . $website_up;
  $insert_uf = substr($insert_uf, 0, -1);
  $wpdb->query("INSERT INTO ".$db_bp_pf_data." ( id, field_id, user_id, value, last_updated ) VALUES $insert_uf");
 }
