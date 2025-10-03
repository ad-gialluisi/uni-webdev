FROM debian:bookworm

# Set UTF-8 encoding
ENV LANG=C.UTF-8


RUN set -eux; \
    \
    ################################# \
    # Installing of the base system # \
    ################################# \
    \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        apache2=2.4.65-1~deb12u1 \
        libapache2-mod-php8.2=8.2.29-1~deb12u1 \
        php8.2-mysql=8.2.29-1~deb12u1 \
        php8.2-mcrypt=3:1.0.5-4 \
        mariadb-client=1:10.11.14-0+deb12u2 \
        mariadb-server=1:10.11.14-0+deb12u2 \
    ; \
    rm -rf /var/lib/apt/lists/* ; \
    \
    ################################### \
    # Configure the webserver and PHP # \
    ################################### \
    rm /var/www/html/index.html ; \
    sed -i 's/;extension=pdo_mysql/extension=pdo_mysql/' /etc/php/8.2/apache2/php.ini ; \
    sed -i 's/display_errors = Off/display_errors = On/' /etc/php/8.2/apache2/php.ini ; \
    sed -i 's/display_startup_errors = Off/display_startup_errors = On/' /etc/php/8.2/apache2/php.ini ; \
    sed -i 's/log_errors = Off/log_errors = On/' /etc/php/8.2/apache2/php.ini ; \
    sed -i 's/html_errors = Off/html_errors = On/' /etc/php/8.2/apache2/php.ini ;


EXPOSE 80

# Deploy the website
COPY --chown=www-data:www-data utils/webserver-contents/. /var/www/html/

# Deploy the utility files
COPY --chmod=744 utils/run.sh /utils/run.sh
COPY --chmod=644 utils/StudyPlatform.sql /utils/StudyPlatform.sql
COPY --chmod=644 utils/SampleContents.sql /utils/SampleContents.sql


# Finally, run mariadb to restore the DB
RUN set -eux; \
    service mariadb start ; \
    mysql -u root -proot < /utils/StudyPlatform.sql ; \
    mysql -u root -proot StudyPlatform < /utils/SampleContents.sql ; \
    service mariadb stop ;


CMD ["utils/run.sh"]