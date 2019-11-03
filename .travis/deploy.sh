#!/bin/bash

# To handle the known hosts output
echo 'connect.welcome2the.cloud ecdsa-sha2-nistp256 SHA256:BXmn96AGAd7TdnQdwxwOuqQOXJ1/VvR0MeZgwqZswp0' >> ~/.ssh/known_hosts

set -e
ssh -i ~/.ssh/deploy_rsa git@connect.welcome2the.cloud -v exit
#
#git config --global push.default simple # we only want to push one branch — master
# specify the repo on the live server as a remote repo, and name it 'production'
# <user> here is the separate user you created for deploying
#git remote add production ssh://git@connect.welcome2the.cloud/Welcome2TheCloud/
#git push production master # push our updates
