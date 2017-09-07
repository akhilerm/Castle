# Castle

An open source implementation of the Google Foobar challenge. The game is modelled in such a way that it is similar in almost every way to the google foobar challenge.
User has to navigate using terminal commands. Solution can be submitted currently in python. The code will be executed in a docker container and will be checked against a set of test cases.
Once a question is completed, the user can request for new challenge.

3 folders where question/answers are stored. In ./storage/app/public/
```
./storage/app/public/
├── answers
│   ├── driver.py
│   ├── driver.sh
│   ├── question_1
│   ├── question_2
│   ├── question_3
│   .
│   . 
│   └── question_n
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

changes in `ShellController.php` in `request()` method so that the solution file for new language is created.
