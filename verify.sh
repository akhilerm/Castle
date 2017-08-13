#!/bin/bash

#arguments filename, question_name
filename=$1
question=$2

tmp_id=$(docker create python:df)
docker run python:df python hello.py
