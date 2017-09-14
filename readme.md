# Castle     
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square)](http://makeapullrequest.com)

An open source implementation of the Google Foobar challenge. The game is modelled in such a way that it is similar in almost every way to the google foobar challenge.
User has to navigate using terminal commands. Solution can be submitted currently in python. The code will be executed in a docker container and will be checked against a set of test cases.
Once a question is completed, the user can request for new challenge.


Uses [jquery terminal](https://github.com/jcubic/jquery.terminal) for creating the terminal interface. [Laravel]( https://github.com/laravel/laravel) framework is used to build the application. 

Contributions are invited to support more programming languages and also documentation.

3 folders where question/answers are stored. In ./storage/app/public/
```
./storage/app/public/
├── answers
│   ├── question_1
│   ├── question_2
│   ├── question_3
│   .
│   . 
│   └── question_n
│
├── driver
│   ├── driver.sh
│   ├── verify.sh
│   ├── driver.py
│   .    
│   .   driver files of other languages
│   └── driver.java
│
├── levels
│   ├── question_1
│   │   └── constraints.txt
│   . 
│   .  
│   └── question_n
│       └── constraints.txt
│
└── users
    ├── 1
    │   ├── journal.txt
    │   ├── question_1
    │   │   ├── constraints.txt
    │   │   └── solution.py
    │   └── readme.txt
    ├── 2
    │   ├── journal.txt
    │   ├── question_k
    │   │   ├── constraints.txt
    │   │   └── solution.py
    │   └── readme.txt
    .
    .
    └── user_id(n)
        ├── journal.txt
        ├── question_k
        │   ├── constraints.txt
        │   └── solution.py
        └── readme.txt
```
### Adding new languages

install the language in the docker container

In the Dockerfile add `RUN apt-get install -y java` for example
    
Rebuild the container

changes in `verify.sh` to recognize the new language. A language is recognized depending on the file extension of the solution.

changes in `driver.sh` . make changes specific to the language in the case statement for the language.

create a new driver file for the language which is invoked from `driver.sh`

changes in `ShellController.php` in `request()` method so that the solution file for new language is created.
