#! /bin/sh

result=$(timeout 20 python /tmp/answer/driver.py 2>&1)
echo $result