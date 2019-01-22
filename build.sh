#!/bin/bash

rm -rf ./built || :

mkdir ./built
cp -r ./public/ ./built
cp -r ./library/ ./built
cp -r ./application/ ./builti


