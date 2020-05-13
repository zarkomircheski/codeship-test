#!/bin/sh

#to app root
cd app

# run phpcs checks
php ./vendor/bin/phpcs --standard=phpcs.xml

# run linter checks
npm run lint-js -- --fix --quiet