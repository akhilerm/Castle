#!/bin/bash

#arguments filename, question_name, working directory
filename=$1
question=$2
workdir=$3

touch $workdir/users/1/$question/__init__.py
docker run -v $workdir/users/1/$question:/tmp/user:ro -v $workdir/answers:/tmp/answer:ro python:df python /tmp/answer/driver.py
rm $workdir/users/1/$question/__init__.py