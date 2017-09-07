#!/bin/bash

set -e
#arguments filename, question_name, working directory, user_id
filename=$1
question=$2
workdir=$3
userid=$4

#can add support for more languages here
if [[ $filename == *".java"* ]];
	then lang="JAVA"
elif [[ $filename == *".py"* ]];
	then lang="PYTHON"
fi

result=$(docker run --rm -v $workdir/users/$userid/$question:/tmp/user:ro -v $workdir/answers:/tmp/answer:ro -v $workdir/drivers:/tmp/driver cont:df /tmp/driver/driver.sh ${lang} ${question})

if [[ $result == "FAIL" ]]; then
    echo "FAIL"
    echo "Resource limit reached"
elif [[ $result == "ERROR" ]]; then
    echo "FAIL"
    echo "Error in file" $filename
else
    echo "SUCCESS"
    echo $result
fi