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
</IfModule>


# Customizable error response
#
ErrorDocument 404 {PREFIX}{errorFile}

# END OOS
