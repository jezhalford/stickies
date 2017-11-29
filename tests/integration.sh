#!/bin/bash

SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"

# Start a PHP server
php -t $SCRIPTPATH/../public/ -S localhost:8000 &


# Send a post request
SPEC=$(cat $SCRIPTPATH/spec.json)
DATA=$(cat $SCRIPTPATH/data.json)
curl -d "spec=$SPEC&data=$DATA" -X POST http://localhost:8000/ -o $SCRIPTPATH/actual.pdf

# Kill the PHP server
kill %1

# Compare the response PDF with an expected PDF
# This generates a "diff" image using imagemagick compare
compare $SCRIPTPATH/actual.pdf $SCRIPTPATH/expected.pdf -compose src $SCRIPTPATH/diff.png

# Inspect the diff image to confirm if it is blank using imagemagick identify
# (see https://superuser.com/a/683272/16391)
identify -verbose  $SCRIPTPATH/diff.png | grep -E "(standard deviation|kurtosis|skewness)" > $SCRIPTPATH/a

echo
echo
diff $SCRIPTPATH/actual.pdf $SCRIPTPATH/expected.pdf
echo

# Compare the output of the identify step with expected values for a blank image
(diff -w $SCRIPTPATH/expected-identify $SCRIPTPATH/a && echo "TEST PASSED!") || echo "TEST FAILED!"

# Tidy up
rm $SCRIPTPATH/a
