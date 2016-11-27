#!/usr/bin/env bash
## Exit immediately if a command exits with a non-zero status.
set -e
#set distribution folder alias
dist="$HOME"/Dropbox/_open/_ayso/s1/web/referee_site/refsched

## clear the screen
#printf "\033c"

echo "  Checkout master branch from Git repository..."
#git checkout master

echo "  Build public resources..."
./node_modules/.bin/gulp build

echo "  Purge composer development items..."
composer install --no-dev

echo "  Clear distribution folder..."
rm -f -r $dist

echo "  Setup distribution folder..."
mkdir $dist
mkdir $dist/var
mkdir $dist/var/uploads
mkdir $dist/src

echo "  Copying app folders to distribution..."
cp -f -r app $dist/app
cp -f -r vendor $dist/vendor
cp -f -r public $dist/public
cp -f -r templates $dist/templates
cp -f -r config $dist/config
cp -f -r src/Action $dist/src

echo "  Updating index to production..."
mv -f $dist/public/app_prod.php $dist/public/app.php

echo "  Removing OSX jetsam..."
find $dist -type f -name '.DS_Store' -delete

echo "  Removing development jetsam..."
find $dist -type f -name 'app_*' -delete
find $dist/src -type f -name '*Test.php' -delete
rm -f -r $dist/config/.git
find $dist/config -type f -name '.env' -delete

echo "  Restore composer development items..."
composer update

echo "...distribution complete"
