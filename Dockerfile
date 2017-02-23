FROM php:5.6-apache
MAINTAINER "steelcode468@gmail.com"

RUN apt-get update && apt-get upgrade -y

RUN mkdir -p /usr/share/php/steelcode-framework

COPY bin            /usr/share/php/steelcode-framework/bin
COPY extensions     /usr/share/php/steelcode-framework/extensions
COPY includes       /usr/share/php/steelcode-framework/includes
COPY library        /usr/share/php/steelcode-framework/library
COPY steelcode.php  /usr/share/php/steelcode-framework/steelcode.php

RUN ln -sf /usr/share/php/steelcode-framework/bin/steelcode /usr/local/bin/steelcode

EXPOSE 80
EXPOSE 443

