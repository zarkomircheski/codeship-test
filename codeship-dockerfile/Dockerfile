FROM alpine:3.9.6

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
RUN mkdir /var/www/ && chown -R 1000:1000 /var/www/
RUN mkdir /var/www/app/ && chown -R 1000:1000 /var/www/app/
RUN mkdir /var/www/composer_packages/ && chown -R 1000:1000 /var/www/composer_packages/
RUN mkdir /var/www/node_packages/ && chown -R 1000:1000 /var/www/node_packages/

# Copy config files for linter and composer
COPY ./package.json /var/www/node_packages/package.json
COPY ./.eslintrc.json /var/www/node_packages/.selintrc.json
COPY ./composer.json /var/www/composer_packages/composer.json

# prepare npm dir for user
RUN mkdir /.npm && chown -R 1000:1000 /.npm
RUN mkdir /.config && chown -R 1000:1000 /.config

# switch to user 1000 - this user is used on running container
USER 1000

# Install node packages
RUN cd /var/www/node_packages && npm install

# switch back to root
USER root

# Install composer packages
RUN cd /var/www/composer_packages && composer install --prefer-dist --no-scripts --no-autoloader && rm -rf /root/.composer
RUN cd /var/www/composer_packages && composer dump-autoload --no-scripts --optimize
RUN chown -R 1000:1000 /var/www/composer_packages

# Remove cache and tmp files
RUN rm -rf /var/cache/apk/* && \
    rm -rf /tmp/*

WORKDIR /var/www/