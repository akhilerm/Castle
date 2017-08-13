#!/bin/bash

#arguments filename, question_name, working directory
filename=$1
question=$2
workdir=$3

docker run -it -v $workdir/users/1:/tmp/user:ro -v $workdir/answers:/tmp/answers:ro python:df
