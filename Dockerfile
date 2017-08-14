#FROM debian:jessie

#MAINTAINER Akhil Mohan "akhilerm@gmail.com"

#RUN apt-get update
#RUN apt-get install -y python
#FROM python:df

#RUN mkdir /tmp/user /tmp/answer

#ENV PYTHONPATH=/tmp/user
FROM python:2
CMD python /tmp/answer/driver.py
