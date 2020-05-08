#!/usr/bin/env bash

LOG_FILE=/var/log/rsync-deploy.log
ENV_FILE=/tmp/www/wordpress/fatherly/env.php
if [ "$APPLICATION_NAME" == "CodeDeployBitbucket-Fatherly-staging" ]
    then
        REGION="us-east-2"
    else
        REGION="us-east-1"
fi



MASTER_DB_HOST=$(aws ssm get-parameters --region ${REGION} --names "writer_db_host" --with-decryption --query Parameters[0].Value | tr -d '"')
MASTER_DB_NAME=$(aws ssm get-parameters --region ${REGION} --names "writer_db_name" --with-decryption --query Parameters[0].Value | tr -d '"')
MASTER_DB_USER=$(aws ssm get-parameters --region ${REGION} --names "writer_db_user" --with-decryption --query Parameters[0].Value | tr -d '"')
MASTER_DB_PASS=$(aws ssm get-parameters --region ${REGION} --names "writer_db_pass" --with-decryption --query Parameters[0].Value | tr -d '"')
SLAVE_DB_HOST=$(aws ssm get-parameters --region ${REGION} --names "reader_db_host" --with-decryption --query Parameters[0].Value | tr -d '"')
SLAVE_DB_NAME=$(aws ssm get-parameters --region ${REGION} --names "reader_db_name" --with-decryption --query Parameters[0].Value | tr -d '"')
SLAVE_DB_USER=$(aws ssm get-parameters --region ${REGION} --names "reader_db_user" --with-decryption --query Parameters[0].Value | tr -d '"')
SLAVE_DB_PASS=$(aws ssm get-parameters --region ${REGION} --names "reader_db_pass" --with-decryption --query Parameters[0].Value | tr -d '"')
AMAZON_SECRET_ACCESS_KEY=$(aws ssm get-parameters --region ${REGION} --names "amazon_api_secret_access_key" --with-decryption --query Parameters[0].Value | tr -d '"')




echo "" >> $LOG_FILE
echo -n "Starting rsync: " >> $LOG_FILE
date >> $LOG_FILE

# Create our env file in the tmp directory
touch $ENV_FILE
chown apache:apache $ENV_FILE
#write our environment variables to the env file.
echo -e "<?php\n" >> $ENV_FILE
echo -e  "putenv(\"MASTER_DB_HOST=${MASTER_DB_HOST}\");\n" >> $ENV_FILE
echo -e  "putenv(\"MASTER_DB_NAME=${MASTER_DB_NAME}\");\n" >> $ENV_FILE
echo -e  "putenv(\"MASTER_DB_USER=${MASTER_DB_USER}\");\n" >> $ENV_FILE
echo -e  "putenv(\"MASTER_DB_PASS=${MASTER_DB_PASS}\");\n" >> $ENV_FILE
echo -e  "putenv(\"SLAVE_DB_HOST=${SLAVE_DB_HOST}\");\n" >> $ENV_FILE
echo -e  "putenv(\"SLAVE_DB_NAME=${SLAVE_DB_NAME}\");\n" >> $ENV_FILE
echo -e  "putenv(\"SLAVE_DB_USER=${SLAVE_DB_USER}\");\n" >> $ENV_FILE
echo -e  "putenv(\"SLAVE_DB_PASS=${SLAVE_DB_PASS}\");" >> $ENV_FILE
echo -e  "putenv(\"AMAZON_SECRET_ACCESS_KEY=${AMAZON_SECRET_ACCESS_KEY}\");" >> $ENV_FILE
# Use correct ownership for temporary parent directory
chown apache:apache /tmp/www/wordpress/fatherly

# Copy non-code
rsync -aPog \
    --exclude="*.php" \
    /tmp/www/wordpress/fatherly/ /var/www/wordpress/fatherly/ >> $LOG_FILE

# Copy code
rsync -aPog \
    --include="*.php" \
    /tmp/www/wordpress/fatherly/ /var/www/wordpress/fatherly/ >> $LOG_FILE

date >> $LOG_FILE
