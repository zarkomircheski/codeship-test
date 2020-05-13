#!/bin/sh
# If any commands fail (exit code other than 0) entire script exits

ls -la

ln -s node_packages/node_modules app/node_modules
ln -s composer_packages/vendor app/vendor

cd composer_packages
ls -la

cd ../node_packages
ls -la

cd ../app
ls -la

# echo "---> Installing npm packages"
# npm install
# gulp --version

# # Install composer packages
# composer install --prefer-dist --no-scripts --no-autoloader && rm -rf /root/.composer
# composer dump-autoload --no-scripts --optimize