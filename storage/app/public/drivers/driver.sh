#! /bin/sh

lang=$1
question=$2

cd /tmp/driver

case $lang in
    JAVA )
    ;;

    PYTHON )
    cp ../user/solution.py solution.py
    cp ../answer/$question answer
    result=$(timeout 20 python driver.py  2>&1)
    if [ -z "$result" ];
        then echo "FAIL"
    elif [[ $result == *"Traceback"* ]];
        then echo "ERROR"
    else
        echo $result
    fi
    rm solution.py
    rm answer
    ;;

esac
