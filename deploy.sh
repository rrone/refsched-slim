#!/usr/bin/env bash
## Exit immediately if a command exits with a non-zero status.
set -e
#set folder aliases
ayso="$HOME"/Sites/AYSO
dev="${ayso}"/_dev/refsched-slim
config="${dev}"/config

prod="${ayso}"/_services/rs.ayso1ref.com/rs

PHP=/usr/local/etc/php/7.3/conf.d

## clear the screen
printf "\033c"

echo ">>> Checkout master branch from Git repository..."
#git checkout master
echo

echo ">>> Build production assets..."
#yarn encore production --progress
echo

echo ">>> Purge development items..."
## Disable xdebug for composer performance
if [[ -e ${PHP}"/ext-xdebug.ini" ]]
then
    mv "$PHP"/ext-xdebug.ini "$PHP"/ext-xdebug.~ini
fi

echo ">>> Clear distribution folder..."
rm -rf "${prod:?}"
mkdir "${prod}"
echo

echo ">>> Copying app to distribution..."
cp -f ./*.json "${prod}"
cp -f ./*.lock "${prod}"

mkdir "${prod}"/bin
cp bin/console "${prod}"/bin

echo ">>> Copying app to distribution..."
cp -rf app "${prod}"

echo ">>> Copying config to distribution..."
mkdir "${prod}"/config
cp config/config_prod.php "${prod}"/config/config.php

echo ">>> Copying public to distribution..."
cp -rf public "${prod}"
rm "${prod}/public/app_dev.php"
rm "${prod}/public/app.php"
mv "${prod}/public/app_prod.php" "${prod}/public/app.php"

echo ">>> Copying src to distribution..."
cp -rf src "${prod}"

echo ">>> Copying templates to distribution..."
cp -rf templates "${prod}"

mkdir  "${prod}"/var
mkdir  "${prod}"/var/uploads
echo

echo ">>> Removing OSX jetsam..."
find "${prod}" -type f -name '.DS_Store' -delete
echo

echo ">>> Removing development jetsam..."
find "${prod}"/src -type f -name '*Test.php' -delete
echo

cd "${prod}"
    composer install --no-dev
#    yarn workspaces focus --production
    yarn install

    ln -s public ../public

cd "${dev}"

echo ">>> Re-enable xdebug..."
## Restore xdebug
if [[ -e ${PHP}"/ext-xdebug.~ini" ]]
then
    mv "${PHP}"/ext-xdebug.~ini "${PHP}"/ext-xdebug.ini
fi
echo

echo "...distribution complete"
