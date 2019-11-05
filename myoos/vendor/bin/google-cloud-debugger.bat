@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../google/cloud/Debugger/bin/google-cloud-debugger
php "%BIN_TARGET%" %*
