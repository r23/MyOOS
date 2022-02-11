@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../laravel/homestead/bin/homestead
php "%BIN_TARGET%" %*
