#!/bin/sh

# Symlink composer and node packages into the application root
ln -sf /var/www/node_packages/node_modules /var/www/app/node_modules
ln -sf /var/www/composer_packages/vendor /var/www/app/vendor