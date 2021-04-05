#!/usr/bin/env bash
## Exit immediately if a command exits with a non-zero status.
set -e
#set distribution folder alias
dev="$HOME"/Sites/AYSO/_dev/refsched.slim
prod="$HOME"/Sites/AYSO/_services/rs
config=${dev}/config
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
if [[ -e ${PHP}"/ext-xdebug.ini" ]]
then
    mv "$PHP"/ext-xdebug.ini "$PHP"/ext-xdebug.~ini
fi
echo

echo "  Clear distribution folder..."
rm -rf ${prod}
echo

echo "  Setup distribution folder..."
mkdir ${prod}
mkdir ${prod}/var
mkdir ${prod}/var/uploads
mkdir ${prod}/src
mkdir ${prod}/config
echo

echo "  Copying app folders to distribution..."
cp -rf app ${prod}/app
cp -rf public ${prod}/public
cp -rf templates ${prod}/templates
cp -rf src/Action ${prod}/src
cp -rf ${config}/config_prod.php ${prod}/config/config.php
cp -f *.json ${prod}
cp -f *.lock ${prod}
cp -f license.txt ${prod}

echo "  Updating production libraries..."
cd ${prod}
    composer install --no-dev
    yarn install --prod=true

echo "  Updating index to production..."
cp -f ${dev}/public/app_prod.php ${prod}/public/app.php
echo

echo "  Removing OSX jetsam..."
find ${prod} -type f -name '.DS_Store' -delete
echo

echo "  Removing development jetsam..."
find ${prod} -type f -name 'app_*' -delete
find ${prod}/src -type f -name '*Test.php' -delete
echo

echo "  Restore composer development items..."
## Restore xdebug
if [[ -e ${PHP}"/ext-xdebug.~ini" ]]
then
    mv "$PHP"/ext-xdebug.~ini "$PHP"/ext-xdebug.ini
fi
echo

echo "...distribution complete"