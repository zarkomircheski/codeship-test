#!/bin/sh
# If any commands fail (exit code other than 0) entire script exits

echo "---> Installing npm packages"
npm install
gulp --version

# Install composer packages
composer install --prefer-dist --no-scripts --no-autoloader && rm -rf /root/.composer
composer dump-autoload --no-scripts --optimize