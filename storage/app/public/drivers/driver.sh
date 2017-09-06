#! /bin/sh

lang=$1

case $lang in
    JAVA )
    ;;
    PYTHON )
    cp ../user/solution.py solution.py
    cp ../answer/
    ;;
esac

if

result=$(timeout 20 python solution.py  2>&1)
echo "HI" $result "BY"