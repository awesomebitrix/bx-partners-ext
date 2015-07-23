#!/bin/bash

BX_MODS_DIR=/home/bitrix/www/bitrix/modules
# BX_MODS_DIR=/root/test/bitrix/modules
#ECHO=/bin/echo
ECHO=''

case "$1" in
ON)
    $ECHO mv $BX_MODS_DIR/search_off $BX_MODS_DIR/search
    ls -dl $BX_MODS_DIR/search*
    ;;
OFF)
    $ECHO mv $BX_MODS_DIR/search $BX_MODS_DIR/search_off
    ls -dl $BX_MODS_DIR/search*
    ;;
*)
    echo First argument must be ON or OFF
    ;;
esac

