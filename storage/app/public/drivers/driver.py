#! /usr/bin/python

#should move this file inside docker image
import ast
import solution

'''driver file running the program
	takes the test cases from the answers/question_name file
	and executes each test case. The output of each execution
	will be compared and the program outputs a  binary string.
	Eg : 1110111 means out of 7 test cases 4th failed and rest
	all passed.
	Resource/Time limit errors will be produced from docker container'''

#opening and parsing test cases
with open ("answer") as file: # change after development finishes
    cases=file.readlines();
cases = [x.strip() for x in cases]
cases = [ast.literal_eval(x) for x in cases]

s="" #return string
number_of_cases = len(cases)/2

for i in range(number_of_cases):
    if type(cases[i]) is tuple:
        if cases[number_of_cases+i] == solution.answer(*cases):
            s+="1"
        else:
            s+="0"
    else:
        if cases[number_of_cases+i] == solution.answer(cases[i]):
            s+="1"
        else:
            s+="0"

print s
