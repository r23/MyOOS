<?php defined( 'ABSPATH' ) or die( 'forbidden' );
// (C) 2023 - axew3.com

# The groups/roles integration can be so complex
# It is substantially quite hard to put here any possible combination of things expecting that it will fit the required for any possible scenario.
# This file can be edited as more like, and can be copied into the 'wp-content/plugins/wp-w3all-custom/wpRoles_phpbbGroups.php' folder
# so that when the plugin will update, custom modifications will not be lost


# Vars
# $w3all_roles_to_groups_on_wpupdate_profile (declared on file class.wp.w3all-phpbb.php -> function phpbb_update_profile )
# $w3all_roles_to_groups_on_verify_credentials (declared on file class.wp.w3all-phpbb.php -> function verify_phpbb_credentials )

# $w3all_groups_to_roles_on_verify_credentials (declared on file class.wp.w3all-phpbb.php -> function verify_phpbb_credentials )
# are used to determine which part of code may is not executed, due to options values or because one option could conflict with the other, during some WP profile update process


# phpBB: https://www.phpbb.com/support/docs/en/3.3/ug/adminguide/groups_types/
# some default phpBB group
// group_id 2 = registered
// group_id 4 = global moderator
// group_id 5 = administrator

# WordPress: https://wordpress.org/support/article/roles-and-capabilities/

# used to determine roles against groups
$wp_normal_roles_ary  = ['subscriber', 'contributor', 'author', 'customer'];
$wp_editors_roles_ary = ['editor', 'shop_manager'];
$wp_admins_roles_ary  = ['administrator'];

/*
# Custom additions arrays (NOT used, just a test way)
$wpRolesphpBBGroups = ['administrator' => 5, 'subscriber' => 2, 'contributor' => 2, 'author' => 2, 'editor' => 4];
# shop_manager and customer, refers to Woocommerce capabilities/roles
$wooAndcustom_wpRolesphpBBGroups = ['customer' => 2, 'shop_manager' => 4, 'example_another_role' => 2];
# an array to manage more complex switches between WP Roles and phpBB Groups
$wpRoles_phpBBGroups = array_merge($wpRolesphpBBGroups,$wooAndcustom_wpRolesphpBBGroups);
*/

############################################
### START
# Roles -> Groups switches WHEN ON --> wp_profile_update (see public static function phpbb_update_profile on class.wp.w3all-phpbb.php)
# $uid  = $phpbbug[0]->user_id; (phpbb_update_profile on class.wp.w3all-phpbb.php)
# $ugid = $phpbbug[0]->group_id;(phpbb_update_profile on class.wp.w3all-phpbb.php)
# -> for the updated WP user
# synchronous

if( isset($w3all_roles_to_groups_on_wpupdate_profile) )
{
  // **** MemberPress note: do not know if it is a bug of the version 10 Legacy i am testing on, or a default behavior, but when an user is set as NoRole in WordPress,
  // WP set/leave the user as subscriber by the way.
  // So that it is necessary to detect that the user may have no active mepr subscriptions/memberships and deactivate or move the user into another group in phpBB if the case

  // ** it is possible to add memberships from external wp plugins for more complex roles/groups/memberships switches
  // like for MemberPress memberships or more Woocommerce Roles or something else

  // has been set as NoRole in WP?
     if( empty($wpu->roles) )
      { // if no role for this site deactivate in phpBB
        $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_type = '1' WHERE user_id = '$uid'");
      }

  // if old user's data role do not match, then there is an user role change: update/activate in phpBB based on Roles/Groups array
  // check that the user effectively have a role
   if( empty($old_user_data->roles[0]) && !empty($wpu->roles)
       OR !empty($wpu->roles) && $old_user_data->roles[0] != $wpu->roles[0] ) // **
   { // retrieve all groups which the user belong to (for more complex roles/groups switches)
     # $uid_groups = $w3all_phpbb_connection->get_results("SELECT * FROM ".$w3all_config["table_prefix"]."user_group WHERE user_id = $uid");

          // on duplicate key is not possible, there is no index into the user_group table: remove the record if it exist before to insert again
          // even if the user_group table can contain duplicated values
          // *** Note: maybe (?) the phpBB user should be removed from any other group which may belong to into phpBB OR should be added into more groups?

       if( $wpu->roles[0] == 'author' OR $wpu->roles[0] == 'subscriber' OR $wpu->roles[0] == 'contributor' OR $wpu->roles[0] == 'customer' )
       { # AUTHOR, SUBSCRIBER, CONTRIBUTOR, CUSTOMER set as registered
         if( $ugid != 2 )
         {
          $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_type = '0', user_permissions = '', group_id = '2' WHERE user_id = '$uid'");
          $w3all_phpbb_connection->query("DELETE FROM ".$w3all_config["table_prefix"]."user_group WHERE user_id = '$uid' AND group_id IN('2','$ugid')");
          $w3all_phpbb_connection->query("INSERT INTO ".$w3all_config["table_prefix"]."user_group (group_id, user_id, group_leader, user_pending) VALUES ('2','$uid','0','0')");
         }

       } elseif( $wpu->roles[0] == 'editor' OR $wpu->roles[0] == 'shop_manager' )
         { # EDITOR, SHOP MANAGER set as global moderator
          if( $ugid != 4 )
          {
           $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_type = '0', user_permissions = '', group_id = '4' WHERE user_id = '$uid'");
           $w3all_phpbb_connection->query("DELETE FROM ".$w3all_config["table_prefix"]."user_group WHERE user_id = '$uid' AND group_id IN('4','$ugid')");
           $w3all_phpbb_connection->query("INSERT INTO ".$w3all_config["table_prefix"]."user_group (group_id, user_id, group_leader, user_pending) VALUES ('4','$uid','0','0')");
          }

         } elseif( $wpu->roles[0] == 'administrator' && $ugid != 5 )
           { # ADMINISTRATOR set as administrator
             $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_type = '0', user_permissions = '', group_id = '5' WHERE user_id = '$uid'");
             $w3all_phpbb_connection->query("DELETE FROM ".$w3all_config["table_prefix"]."user_group WHERE user_id = '$uid' AND group_id IN('5','$ugid')");
             $w3all_phpbb_connection->query("INSERT INTO ".$w3all_config["table_prefix"]."user_group (group_id, user_id, group_leader, user_pending) VALUES ('5','$uid','0','0')");
             //$w3all_phpbb_connection->query("DELETE FROM ".$w3all_config["table_prefix"]."user_group WHERE user_id = '$uid' AND group_id IN('2','4','5','$ugid')");
             //$w3all_phpbb_connection->query("INSERT INTO ".$w3all_config["table_prefix"]."user_group (group_id, user_id, group_leader, user_pending) VALUES ('2','$uid','0','0'),('4','$uid','0','0'),('5','$uid','0','0')");
            }
    }

}

# Roles -> Groups switches ON wp_profile_update
### END
############################################


############################################
### START
# WP Roles -> phpBB Groups switches WHEN ON --> verify_credentials
# -> for the current logged in user (and data changed in WP, not due to an explicit WP profile update action, but due to some plugin Role update that fire on the background, may due to a subscribed membership or something else
# asynchronous
# Do not execute this if on WP admin profile update action -> function phpbb_update_profile -> class.wp.w3all-phpbb.php

if( isset($w3all_roles_to_groups_on_verify_credentials) && !isset($w3all_roles_to_groups_on_wpupdate_profile) )
{
    # some default phpBB groups
    // group_id 2 = registered
    // group_id 4 = global moderator
    // group_id 5 = administrator

    // logged users uid and gid
      $ugid = $phpbb_user_session[0]->group_id;
      $uid  = $phpbb_user_session[0]->user_id;

   // NoRole in WP?
     if( empty($current_user->roles) )
      { // if no role for this site deactivate in phpBB
        $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_type = '1' WHERE user_id = '$uid'");
      }

   if( !empty($current_user->roles) )
   {
       if( $current_user->roles[0] == 'author' OR $current_user->roles[0] == 'subscriber' OR $current_user->roles[0] == 'contributor' OR $current_user->roles[0] == 'customer' )
       { # AUTHOR, SUBSCRIBER, CONTRIBUTOR, CUSTOMER set as registered
         if( $phpbb_user_session[0]->group_id != 2 )
         {
          $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_type = '0', user_permissions = '', group_id = '2' WHERE user_id = '$uid'");
          $w3all_phpbb_connection->query("DELETE FROM ".$w3all_config["table_prefix"]."user_group WHERE user_id = '$uid' AND group_id IN('2','$ugid')");
          $w3all_phpbb_connection->query("INSERT INTO ".$w3all_config["table_prefix"]."user_group (group_id, user_id, group_leader, user_pending) VALUES ('2','$uid','0','0')");
         }
       } elseif( $current_user->roles[0] == 'editor' OR $current_user->roles[0] == 'shop_manager' )
         { # EDITOR, SHOP MANAGER set as global moderator
          if( $phpbb_user_session[0]->group_id != 4 )
          {
           $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_type = '0', user_permissions = '', group_id = '4' WHERE user_id = '$uid'");
           $w3all_phpbb_connection->query("DELETE FROM ".$w3all_config["table_prefix"]."user_group WHERE user_id = '$uid' AND group_id IN('4','$ugid')");
           $w3all_phpbb_connection->query("INSERT INTO ".$w3all_config["table_prefix"]."user_group (group_id, user_id, group_leader, user_pending) VALUES ('4','$uid','0','0')");
          }

         } elseif( $current_user->roles[0] == 'administrator' && $phpbb_user_session[0]->group_id != 5 )
           { # ADMINISTRATOR set as administrator, global mod and registered
             $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_type = '0', user_permissions = '', group_id = '5' WHERE user_id = '$uid'");
             //$w3all_phpbb_connection->query("DELETE FROM ".$w3all_config["table_prefix"]."user_group WHERE user_id = '$uid' AND group_id IN('5','$ugid')");
             //$w3all_phpbb_connection->query("INSERT INTO ".$w3all_config["table_prefix"]."user_group (group_id, user_id, group_leader, user_pending) VALUES ('5','$uid','0','0')");
             $w3all_phpbb_connection->query("DELETE FROM ".$w3all_config["table_prefix"]."user_group WHERE user_id = '$uid' AND group_id IN('2','4','5','$ugid')");
             $w3all_phpbb_connection->query("INSERT INTO ".$w3all_config["table_prefix"]."user_group (group_id, user_id, group_leader, user_pending) VALUES ('2','$uid','0','0'),('4','$uid','0','0'),('5','$uid','0','0')");
           }
  }
}

# Roles -> Groups switches ON verify_credentials
### END
############################################


############################################
### START
# Groups -> Roles switches WHEN ON --> verify_credentials
# -> for the current logged in user (and data changed in phpBB)
# asynchronous

if( isset($w3all_groups_to_roles_on_verify_credentials) )
{
    # some default phpBB groups
    // group_id 2 = registered
    // group_id 4 = global moderator
    // group_id 5 = administrator

        if($phpbb_user_session[0]->group_id == 2 && !in_array($current_user->roles[0],$wp_normal_roles_ary))
        {
          $usr = new WP_User($current_user->ID);
          $usr->remove_role($current_user->roles[0]); # should remove all roles instead and not only the primary? Or should add to existent?
          $usr->set_role('subscriber');
          $refresh_u = true;

        } elseif($phpbb_user_session[0]->group_id == 4 && !in_array($current_user->roles[0],$wp_editors_roles_ary))
          {
            $usr = new WP_User($current_user->ID);
            $usr->remove_role($current_user->roles[0]); # should remove all roles instead and not only the primary? Or should add to existent?
            $usr->set_role('editor');
            $refresh_u = true;

          } elseif($phpbb_user_session[0]->group_id == 5 && $current_user->roles[0] != 'administrator')
            {
             # $usr = new WP_User($current_user->ID);
             # $usr->remove_role($current_user->roles[0]);
             # $usr->set_role('administrator');
             # $refresh_u = true;
            }


    if(isset($refresh_u))
    {
      #if ( defined( 'WP_ADMIN' ) )
      #{
       if ( !function_exists( 'refresh_user_details' ) ) {
          require_once ABSPATH . '/wp-admin/includes/ms.php';
        }
       refresh_user_details($current_user->ID);
      #}
      clean_user_cache($current_user->ID);
    }

}

# Groups -> Roles switches ON verify_credentials
### END
############################################


/*
# ABOUT memberpress

# function that return specified membership state
  if( class_exists('MeprUser') ){
   if(current_user_can('mepr-active','rules:111')){
    # the user belong and is active on Rule ID 111
   }
  }


# function that return all active mepr memberships
function w3all_mepr_active_umemberships( $user_id = false ){

    if( class_exists('MeprUser') ){

        if( ! $user_id ){
           return false;
        }
     # ... code to get all mepr user's active memberships
    } else {
        return false;
    }
}

# END ABOUT memberpress
*/