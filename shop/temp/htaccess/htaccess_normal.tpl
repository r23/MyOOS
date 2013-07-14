# BEGIN OOS
DirectoryIndex {PREFIX}{indexFile}

# Fix certain PHP values
# (commented out by default to prevent errors occuring on certain
# servers)

<IfModule mod_php4.c>
  php_flag register_globals off
  php_value session.use_trans_sid 0
  php_value magic_quotes_gpc 1
  php_value allow_call_time_pass_reference 1
  php_value error_repoting 2039
  php_value display_errors 0
  php_value log_errors 1
  php_value error_log {logFile}
</IfModule>

# END OOS
