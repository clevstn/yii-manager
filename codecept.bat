@echo off

@setlocal

set BIN_TARGET=%~dp0/vendor/codeception/codeception/codecept

php "%BIN_TARGET%" %*

@endlocal