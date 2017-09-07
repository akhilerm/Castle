#! /bin/bash

lang=$1
question=$2

cd /tmp/driver

case $lang in

    JAVA )
    #copy solution and answer file
    cp ../user/solution.java Answer.java
    cp ../answer/$question answer

    rm answer
    rm Answer.java
    rm Answer.class
    ;;

    PYTHON )
    #copy solution file and answer file
    cp ../user/solution.py solution.py
    cp ../answer/$question answer
    result=$(timeout 20 python driver.py  2>&1)
    if [ -z "$result" ]; then
        echo "FAIL"
    elif [[ $result == *"Traceback"* ]]; then
        echo "ERROR"
    else
        echo $result
    fi
    #removing the copied files and output files
    rm solution.py
    rm solution.pyc
    rm answer
    ;;

esac
