#!/usr/bin/env bash
#
# Author: Philipp A. Mohrenweiser
# Contact: mailto:phiamo@googlemail.com
# Version: $id$
# 
# after composer ran, update the app
# 

set +e
scriptPath=$(readlink -f "${0%/*}")

function usage() {
    cat << EOF
Usage: $(basename $0) <options> -t

This script updates a sf2 environment after RemoteUpdateBundle started a composer update

OPTIONS:
   -h Show this help
   -s Skip installing and dumping of assets
   -d Without db update
   -r With db drop and recreate
   -a With ACL initialisation (only with -d)
   -f With Fixture loading (only with -d)
   -w With Webserver restart
   -c With chmodding of dirs (see envvars file)
EOF
}

APP=$(readlink -f "$scriptPath/../app/console") # assuming we are in bin/
APACHE_RUN_USER="${APACHE_RUN_USER-www-data}"
APACHE_RUN_GROUP="${APACHE_RUN_GROUP-www-data}"
DIRS="${DIRS-app/cache app/logs web/media/}"
WITHASSETS=1
WITHDB=1
WITHDBDROP=0
WITHWEBSERVER=0
WITHDBACL=0
WITHDBFIXTURES=0
WITHCHMOD=0

if [ -e "$scriptPath/envvars" ]; then
    . $scriptPath/envvars
    WITHCHMOD=1
fi

while getopts "hsdrafwc" OPTION ; do
    case $OPTION in
        s)
            WITHASSETS=0
            ;;
        d)
            WITHDB=0
            ;;
        r)
            WITHDBDROP=1
            ;;
        a)
            WITHDBACL=1
            ;;
        f)
            WITHDBFIXTURES=1
            ;;
        w)
            WITHWEBSERVER=1
            ;;
        c)
            WITHCHMOD=0
            ;;
        ?)
            usage
            exit 1
            ;;
    esac
done

if [ $WITHDB = 1 ]; then
    # create db
    if [ $WITHDBDROP = 1 ]; then
        $APP doctrine:database:drop --force
        $APP doctrine:database:create
    fi
    $APP doctrine:schema:update --force
    if [ $WITHDBACL = 1 ]; then
        $APP init:acl
    fi
    if [ $WITHDBFIXTURES = 1 ]; then
        $APP doctrine:fixtures:load
    fi
else
    $APP doctrine:schema:update --force
fi

if [ $WITHASSETS = 1 ]; then
    $APP assets:install --symlink web
    $APP cache:clear --env=prod # dev cache was cleared by composer before
    $APP assets:install --env=prod web
    $APP assetic:dump # should dump all assets including prod and dev
fi

if [ $WITHWEBSERVER = 1 ]; then
echo "restarting your webserver:"
    if [ -e "/etc/init.d/apache2" ]; then
        sudo /etc/init.d/apache2 restart
    elif [ -e "/etc/init.d/nginx" ]; then
        sudo /etc/init.d/nginx restart
        sudo /etc/init.d/php5-fpm restart
    elif [ -e "/etc/init.d/lighttpd" ]; then
        sudo /etc/init.d/lighttpd restart
    else
        echo "NOT WEBSERVER FOUND!"
    fi
fi

if [ $WITHCHMOD = 1 ]; then
    echo "RUNNING CHMOD AS $APACHE_RUN_USER:$APACHE_RUN_GROUP on $DIRS"
    sudo chown --silent $APACHE_RUN_USER.$APACHE_RUN_GROUP -R $DIRS
    sudo chmod --silent 765 -R $DIRS
fi
