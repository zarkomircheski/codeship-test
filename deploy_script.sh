#!/bin/sh

#to app root
cd app

#push to repo

CI_COMMITTER_EMAIL=codechip@test.com
CI_COMMITTER_NAME=codeship_ci
USERNAME=zarkomircheski
TOKEN=e37452bad8c6149033514d87a2923e314e19a836
REPO=codeship-test

# git remote add ${REPO} https://${TOKEN}@github.com/${USERNAME}/${REPO}.git

git config user.email CI_COMMITTER_EMAIL
git config user.name CI_COMMITTER_NAME
git config core.ignorecase false

git add --all

git status
git commit -m "Deployment to $REPO by $CI_COMMITTER_NAME from $CI_NAME"

git push ${REPO} master

git remote rm ${REPO}