#!/bin/bash

# To handle the known hosts output
ssh-keyscan -H connect.welcome2the.cloud >> ~/.ssh/known_hosts

set -e
# ssh -i ~/.ssh/deploy_rsa git@connect.welcome2the.cloud -v exit # Testing to see if we can connect
#
git config --global push.default simple # we only want to push one branch — master
# specify the repo on the live server as a remote repo, and name it 'production'
# <user> here is the separate user you created for deploying
rsync -r --delete-after --quiet $TRAVIS_BUILD_DIR/ git@connect.welcome2the.cloud:/home/git/Welcome2TheCloud/ # This rsyncs the files 