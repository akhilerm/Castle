FROM debian:jessie

MAINTAINER Akhil Mohan "akhilerm@gmail.com"

RUN apt-get update
RUN apt-get install -y python
RUN mkdir /tmp/user /tmp/answer

ENV PYTHONPATH=/tmp/user

CMD /tmp/answer/driver.sh

#should include code to remove unwanted modules from python in next build
#should include driver files in the image itself