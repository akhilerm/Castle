FROM debian:jessie

MAINTAINER Akhil Mohan "akhilerm@gmail.com"

RUN apt-get update
RUN apt-get install -y python
RUN mkdir /tmp/user /tmp/answer

ENV PYTHONPATH=/tmp/user

CMD timeout 20 python /tmp/answer/driver.py

#should include code to remove unwanted modules from python in next build