#!/usr/bin/env bash
## Exit immediately if a command exits with a non-zero status.
set -e
#set distribution folder alias
dist="$HOME"/GoogleDrive-ayso1sra/s1/web/ayso1ref/services/rs
config="$HOME"/Sites/AYSO/refsched/config
PHP=/usr/local/etc/php/7.3/conf.d

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
if [[ -e $PHP"/ext-xdebug.ini" ]]
then
    mv "$PHP"/ext-xdebug.ini "$PHP"/ext-xdebug.~ini
fi
composer install --no-dev
echo

echo "  Clear distribution folder..."
rm -f -r $dist
echo

echo "  Setup distribution folder..."
mkdir $dist
mkdir $dist/var
mkdir $dist/var/uploads
mkdir $dist/src
mkdir $dist/config
echo

echo "  Copying app folders to distribution..."
cp -f -r app $dist/app
cp -f -r vendor $dist/vendor
cp -f -r public $dist/public
cp -f -r templates $dist/templates
cp -f -r $config/config_prod.php $dist/config/config.php
cp -f -r src/Action $dist/src

echo "  Updating index to production..."
mv -f $dist/public/app_prod.php $dist/public/app.php
echo

echo "  Removing OSX jetsam..."
find $dist -type f -name '.DS_Store' -delete
echo

echo "  Removing development jetsam..."
find $dist -type f -name 'app_*' -delete
find $dist/src -type f -name '*Test.php' -delete
##rm -f -r $dist/config/.git
##find $dist/config -type f -name '.env' -delete
echo

echo "  Restore composer development items..."
## Restore xdebug
if [[ -e $PHP"/ext-xdebug.~ini" ]]
then
    mv "$PHP"/ext-xdebug.~ini "$PHP"/ext-xdebug.ini
fi
composer install
echo

echo "...distribution complete"

