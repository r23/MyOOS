# BEGIN OOS
DirectoryIndex {PREFIX}{indexFile}

<IfModule mod_rewrite.c>
  RewriteEngine On

# you have ERROR 403 try this...
# Options +FollowSymlinks

# Spambots


#  Uncomment following line if your webserver's URL 
#  is not directly related to physival file paths.
#  Update YourShopDirectory (just / for root)

  RewriteBase {PREFIX}

#
#  Rules
#

 RewriteRule ^(.*)-p-(.*).html$ {indexFile}?content=product_info&products_id=$2&rewrite=true& [L,NC,QSA]
 RewriteRule ^(.*)-c-(.*).html$ {indexFile}?content=shop&cPath=$2&rewrite=true& [L,NC,QSA]
 RewriteRule ^(.*)-m-(.*).html$ {indexFile}?content=shop&manufacturers_id=$2&rewrite=true& [L,NC,QSA]
 RewriteRule (.*\.html?) index.php?mp=info&file=$1 [L,QSA]
</IfModule>

# Fix certain PHP values
# (commented out by default to prevent errors occuring on certain
# servers)

  php_flag register_globals off
  php_value session.use_trans_sid 0
  php_value magic_quotes_gpc 1
  php_value allow_call_time_pass_reference 1
  php_value error_repoting 2039
  php_value display_errors 0
  php_value log_errors 1
  php_value error_log {logFile}


# Customizable error response
#
ErrorDocument 404 {PREFIX}{errorFile}

# END OOS
