FROM alpine:3.9.6

RUN cd /tmp/

# Update apk and install needed tools
RUN apk --no-cache --update add curl git ca-certificates openssh-client
  
# Install php
RUN apk --no-cache add \
# php7-common \
# php7-xmlreader \
# php7-session \
# php7-fpm \
# php7-zlib \
# php7-xml \
# php7-pdo \
# php7-gd \
# php7-curl \
# php7-opcache \
# php7-ctype \
# php7-mbstring \
# php7-soap \
# php7-intl \
# php7-bcmath \
# php7-dom \
# php7-redis \
php7-json \
php7-phar \
php7-iconv \
php7-openssl \
php7-tokenizer \
php7-xmlwriter \
php7-simplexml \
php7

# Small fixes to php & nginx
RUN ln -s /etc/php7 /etc/php && \
  # ln -s /usr/bin/php7 /usr/bin/php && \
    ln -s /usr/sbin/php-fpm7 /usr/bin/php-fpm && \
    ln -s /usr/lib/php7 /usr/lib/php


# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install nodejs for building frontend assets
RUN apk --no-cache add nodejs nodejs-npm


# Make directories for composer and node packages
RUN mkdir /var/www/
RUN mkdir /var/www/app/
RUN mkdir /var/www/composer_packages/
RUN mkdir /var/www/node_packages/

# Copy config files for linter and composer
COPY ./package.json /var/www/node_packages/package.json
COPY ./.eslintrc.json /var/www/node_packages/.selintrc.json
COPY ./composer.json /var/www/composer_packages/composer.json



# Install node packages
RUN cd /var/www/node_packages && npm install

# Install composer packages
RUN cd /var/www/composer_packages && composer install --prefer-dist --no-scripts --no-autoloader && rm -rf /root/.composer
RUN cd /var/www/composer_packages && composer dump-autoload --no-scripts --optimize

# Remove cache and tmp files
RUN rm -rf /var/cache/apk/* && \
    rm -rf /tmp/*

WORKDIR /var/www/app

# TRY INSTALLING NPM PACKAGES GLOBALLY ON BUILD, THAN SYMLINK THE NODE_MODULES IN PROJECT DIR ON RUN "build_script"
# DONT FORGET TO INCLUDE CONFIG FILES IN DOCKERFILE