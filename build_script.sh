#!/bin/sh
# If any commands fail (exit code other than 0) entire script exits
# set -e

# CI_COMMITTER_EMAIL=codechip@test.com
# CI_COMMITTER_NAME=codeship_ci
# USERNAME=zarkomircheski
# TOKEN=e37452bad8c6149033514d87a2923e314e19a836
# REPO=codeship-test-test

# git --version

# git clone https://${TOKEN}@github.com/${USERNAME}/${REPO}.git /app
# npx install-peerdeps --dev eslint-config-airbnb-base

# echo "---> Installing npm packages"
npm install
gulp --version

# Install composer packages
composer install --prefer-dist --no-scripts --no-autoloader && rm -rf /root/.composer
composer dump-autoload --no-scripts --optimize