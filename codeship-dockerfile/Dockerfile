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

WORKDIR /var/www/

# Remove cache and tmp files
RUN rm -rf /var/cache/apk/* && \
    rm -rf /tmp/*