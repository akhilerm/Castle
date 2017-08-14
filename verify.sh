#!/bin/bash

#arguments filename, question_name, working directory
filename=$1
question=$2
workdir=$3

#should edit question_name in driver.py with correct $question and replace it after completion
touch $workdir/users/1/$question/__init__.py
result=$(docker run --rm -v $workdir/users/1/$question:/tmp/user:ro -v $workdir/answers:/tmp/answer:ro python:df python /tmp/answer/driver.py)

rm $workdir/users/1/$question/__init__.py