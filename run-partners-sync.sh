#!/bin/bash

. /usr/local/bin/bashlib

LOG=`namename $0`.log

exec 1>$LOG 2>&1
date +"Start update sites at "%F_%T
# /bin/bash $HOME/bx-module-search-ctl.sh OFF
/usr/bin/php $HOME/www/adm/run-sync_cron_auto_update.php
# /bin/bash $HOME/bx-module-search-ctl.sh ON
date +"Finish update sites at "%F_%T

# cd www/adm/
# php ./run-search-reindex.php
# date +"Finish reindex sites at "%F_%T
