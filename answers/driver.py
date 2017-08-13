#! /usr/bin/python

import ast
from solution import main
'''driver file running the program
	takes the test cases from the answers/question_name file
	and executes each test case. The output of each execution
	will be compared and the program outputs a  binary string.
	Eg : 1110111 means out of 7 test cases 4th failed and rest
	all passed.
	Resource/Time limit errors will be produced from docker container'''

#opening and parsing test cases
with open ("question_1") as file:
    cases=file.readlines();
cases = [x.strip() for x in cases]
cases = [ast.literal_eval(x) for x in cases]

s="" #return string

for case in cases:
    if type(case) is tuple:
        if main(*case):
            s+="1"
        else:
            s+="0"
    else:
        if main(case):
            s+="1"
        else:
            s+="0"

