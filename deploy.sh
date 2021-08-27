#!/usr/bin/env bash
## Exit immediately if a command exits with a non-zero status.
set -e
#set folder aliases
ayso="$HOME"/Sites/AYSO
dev="${ayso}"/_dev/refsched-master
config="${dev}"/config

prod="${ayso}"/_services/rs.ayso1ref.com

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
mkdir "${prod}"/rs
mkdir "${prod}"/rs/config
echo

echo ">>> Copying app to distribution..."
cp -f ./*.json "${prod}"/rs
cp -f ./*.lock "${prod}"/rs

mkdir "${prod}"/rs/bin
cp bin/console "${prod}"/rs/bin

echo ">>> Copying config to distribution..."
cp -rf app "${prod}/rs"
cp -rf "${config}/config_prod.php" "${prod}"/rs/config/config.php
cp -rf public "${prod}/rs"
rm "${prod}/rs/public/app_dev.php"
rm "${prod}/rs/public/app.php"
mv "${prod}/rs/public/app_prod.php" "${prod}/rs/public/app.php"
cp -rf src "${prod}/rs"
cp -rf templates "${prod}"/rs
mkdir  "${prod}"/rs/var
mkdir  "${prod}"/rs/uploads
echo

echo ">>> Removing OSX jetsam..."
find "${prod}" -type f -name '.DS_Store' -delete
echo

echo ">>> Removing development jetsam..."
find "${prod}"/rs/src -type f -name '*Test.php' -delete
echo

cd "${prod}"/rs
    composer install --no-dev
#    yarn workspaces focus --production
    yarn install

    ln -s public ../public_html

cd "${dev}"

echo ">>> Re-enable xdebug..."
## Restore xdebug
if [[ -e ${PHP}"/ext-xdebug.~ini" ]]
then
    mv "${PHP}"/ext-xdebug.~ini "${PHP}"/ext-xdebug.ini
fi
echo

echo "...distribution complete"
