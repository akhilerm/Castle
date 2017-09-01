#!/bin/bash

set -e
#arguments filename, question_name, working directory, user_id
filename=$1
question=$2
workdir=$3
userid=$4

#preparing folder for code testing. changing test case file name and creating python module by __init__.py
sed -i s/question_test_file/$question/g $workdir/answers/driver.py
touch $workdir/users/$userid/$question/__init__.py

result=$(docker run --rm -v $workdir/users/$userid/$question:/tmp/user:ro -v $workdir/answers:/tmp/answer:ro python:df)

#reverting back the changes made before execution
rm $workdir/users/$userid/$question/__init__.py
sed -i s/$question/question_test_file/g $workdir/answers/driver.py

if [ -z "$result" ];
    then echo "FAIL"
    echo "Resource limit reached"
elif [[ $result == *"Traceback"* ]];
    then echo "FAIL"
    echo "Error in "$filename
else
    echo "TRUE"
    echo $result
fi