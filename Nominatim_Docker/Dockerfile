FROM ubuntu:16.04
MAINTAINER Anthony Guiot<anthony.guiot@univ-lorraine.fr>

RUN apt-get update -qq && \
    apt-get install locales && \
    locale-gen en_US.UTF-8 && \
    update-locale LANG=en_US.UTF-8

# Install packages 
RUN apt-get install -y build-essential cmake g++ libboost-dev libboost-system-dev \
                        libboost-filesystem-dev wget curl libexpat1-dev zlib1g-dev libxml2-dev\
                        libbz2-dev libpq-dev libgeos-dev libgeos++-dev libproj-dev  build-essential python-dev python-setuptools libboost-python-dev libboost-thread-dev python-pip \
                        postgresql-server-dev-9.5 postgresql-9.5-postgis-2.2 postgresql-contrib-9.5 \
                        apache2 php php-pgsql sudo libapache2-mod-php php-pear php-db \
                        php-intl git && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    rm -rf /tmp/* /var/tmp/*
COPY postgresql.conf /etc/postgresql/9.5/main/postgresql.conf
RUN PATH="/usr/lib/postgresql/9.5/bin:$PATH"


# Configure postgres

RUN echo "host nominatim  nominatim    0.0.0.0/0  md5" >> /etc/postgresql/9.5/main/pg_hba.conf 

RUN mkdir -p /var/run/postgresql/9.5-main.pg_stat_tmp
RUN chown postgres:postgres /var/run/postgresql/9.5-main.pg_stat_tmp -R

# Nominatim install

RUN wget http://www.nominatim.org/release/Nominatim-3.0.0.tar.bz2 && \
tar xf Nominatim-3.0.0.tar.bz2 
WORKDIR Nominatim-3.0.0
RUN mkdir build 
WORKDIR build 
RUN cmake .. && \
 make


ADD install_small install_small
ADD install_planet install_planet
COPY changepassword.php changepassword.php
COPY settings.php /settings/settings.php

COPY install_small.sh install_small.sh
COPY install_planet.sh install_planet.sh
COPY install_custom.sh install_custom.sh
COPY update_nominatim_planet_database.sh update_nominatim_planet_database.sh
COPY update_nominatim_small_database.sh update_nominatim_small_database.sh
RUN chmod +x  install_small.sh && \
    chmod +x install_planet.sh && \
    chmod +x install_custom.sh && \
    chmod +x update_nominatim_small_database.sh && \
    chmod +x update_nominatim_planet_database.sh
COPY entrypoint.sh /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
