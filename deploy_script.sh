#!/bin/sh
# If any commands fail (exit code other than 0) entire script exits
# set -e

CI_COMMITTER_EMAIL=codechip@test.com
CI_COMMITTER_NAME=codeship_ci
USERNAME=zarkomircheski
TOKEN=e37452bad8c6149033514d87a2923e314e19a836
REPO=codeship-test

git remote add [REPO] https://[USERNAME]:[TOKEN]@github.com/[USERNAME]/[REPO].git

git config --global user.email CI_COMMITTER_EMAIL
git config --global user.name CI_COMMITTER_NAME
git config core.ignorecase false
git add --all
git commit -am "Deployment to ${target_wpe_install} $REPO by $CI_COMMITTER_NAME from $CI_NAME"

git push [REPO] master