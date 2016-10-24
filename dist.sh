#!/bin/bash
set -e

#git checkout master

rm -f -r dist

mkdir dist
mkdir dist/var
mkdir dist/src

cp -f -r app dist/app
cp -f -r vendor dist/vendor
cp -f -r public dist/public
mv -f dist/public/app_prod.php dist/public/app.php
cp -f -r templates dist/templates
cp -f -r config dist/config
cp -f -r src/Action dist/src

find dist -type f -name '.DS_Store' -delete
find dist -type f -name 'app_*' -delete
