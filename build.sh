#!/bin/bash

rm -rf ./built || :

mkdir -p ./built/library && mkdir -p ./built/application && mkdir ./built/public
cp -r ./public/ ./built/public
cp -r ./library/ ./built/library
cp -r ./application/ ./built/application

cat << EOF > ./built/index.php
<?php
require 'public/index.php';
EOF
