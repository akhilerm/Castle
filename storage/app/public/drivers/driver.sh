#! /bin/sh



result=$(timeout 20 python solution.py  2>&1)
echo "HI" $result "BY"