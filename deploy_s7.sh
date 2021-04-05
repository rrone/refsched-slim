#!/usr/bin/env bash
## Exit immediately if a command exits with a non-zero status.
set -e
#set folder aliases
ayso="$HOME"/Sites/AYSO

dev="${ayso}"/_dev/rs7.dev.slim

config=${dev}/config
prod="${ayso}"/_services/rs7.ayso1ref.com

PHP=/usr/local/etc/php/8.0/conf.d

## clear the screen
#printf "\033c"

echo "  Checkout master branch from Git repository..."
#git checkout master
echo

echo "  Build public resources..."
gulp build
echo

echo "  Purge composer development items..."
## Disable xdebug for composer performance
if [[ -e ${PHP}"/ext-xdebug.ini" ]]
then
    mv "$PHP"/ext-xdebug.ini "$PHP"/ext-xdebug.~ini
fi
composer install --no-dev
yarn install --prod=true
echo

echo "  Clear distribution folder..."
rm -f -r ${prod}
echo

echo "  Setup distribution folder..."
mkdir ${prod}
mkdir ${prod}/var
mkdir ${prod}/var/uploads
mkdir ${prod}/src
mkdir ${prod}/config
echo

echo "  Copying app folders to distribution..."
cp -f -r app ${prod}/app
cp -f -r vendor ${prod}/vendor
cp -f -r node_modules ${prod}/node_modules
cp -f -r public ${prod}/public
cp -f -r templates ${prod}/templates
cp -f -r ${config}/config_prod.php ${prod}/config/config.php
cp -f -r src/Action ${prod}/src

echo "  Updating index to production..."
cp -f ${dev}/public/app_prod.php ${prod}/public/app.php
echo

echo "  Removing OSX jetsam..."
find ${prod} -type f -name '.DS_Store' -delete
echo

echo "  Removing development jetsam..."
find ${prod} -type f -name 'app_*' -delete
find ${prod}/src -type f -name '*Test.php' -delete
##rm -f -r $dist/config/.git
##find $dist/config -type f -name '.env' -delete
echo

echo "  Restore composer development items..."
## Restore xdebug
if [[ -e ${PHP}"/ext-xdebug.~ini" ]]
then
    mv "$PHP"/ext-xdebug.~ini "$PHP"/ext-xdebug.ini
fi
composer install
yarn install
echo

echo "...distribution complete"