# Fatherly Setup
Welcome to Fatherly! This setup will guide you to quickly getting started with Fatherly Wordpress Site.

## Style Guide

For Javascript, we use the Airbnb Style Guide: https://github.com/airbnb/javascript

You should run eslint to test your code before committing: `npm run lint-js`

For PHP, we use the PSR specs 1 & 2 https://www.php-fig.org/psr/ 

You should run phpcs before committing your code. If you have composed installed then you can run 
`composer install` from the root of the project and then run `vendor/bin/phpcs --standard=phpcs.xml`.

Some errors can be fixed automatically by running `vendor/bin/phpcbf --standard=phpcs.xml`. phpcbf is included in the
same package as phpcs.

## Installation

Follow the instructions below for installing Fatherly's Wordpress environment.

### Step 1
Clone Fatherly to your localhost. SSH must be setup in Bitbucket first. (You can also clone via Sourcetree)

    git clone git@bitbucket.org:fatherly/wordpress.git /path/to/folder
    cd /path/to/folder
    
    
### Step 2
Go to https://wp-cli.org/#installing to download and install WP-CLI in a separate folder

### Step 3
After you verified WP-CLI has been installed, we need to install Wordpress core files
    
    wp core download --path=/path/to/folder

### Step 4
We need to copy the plug-ins over from the plug-ins zip file. The plug-ins are not stored in Wordpress because WP-Engine does not recommend that action. The zip may be outdated as well. (It's a bad system, and we're working to fix it)

    unzip plugins.zip -d ./wp-content
### Step 5
Next we need to copy the correct docker information that fits your systems. Copy the docker-compose.sample.yml to docker-compose.yml and modify the settings as needed. DO NOT modify the docker-compose.sample.yml. (You can modify the ports here)

    cp docker-compose.sample.yml docker-compose.yml
    
### Step 6
If you have not already installed postgres go here https://bitbucket.org/fatherly/gcloud-analytics/src/master/ and set that up before continuing
TODO: containerize postgres so this is all done in docker      
    
### Step 7  
Next you need to build your image. Make sure you are in the directory's root and run:
    
    docker build . -t wordpresspostgres

### Step 8
Now we build docker on your system.

    docker-compose up

### Step 9
Verify everything is up by going to localhost:8000 or whatever port you choose in step 5, and you should see response asking to do a fresh install of Wordpress (don't install it).

### Step 10
Next we have to populate the database with information we have stored in the SQL folder. Please ask an administrator the SQL file, no SQL file with sensitive informations should be committed to the repo. The database information should match the information in your docker-compose.yml (You can also use a GUI with the below credentials and port)

    mysql -h 127.0.0.1 -u wordpress --password=wordpress --port=3307 < fatherly_file.sql

### Step 11
In your wp-config.php, we need to make sure the site is not re-directed. Add this anywhere near the top of section of the wp-config.php

    /** Ensure the local host is used */ 
    define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST']);
    define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST']);
    define('ENV','dev');

## Step 12
In your wp-config.php 

Change:
    
    define('DB_CHARSET', 'utf8');
    
To:
    
    define('DB_CHARSET', 'utf8mb4');


### Step 13
Update the table prefix variable so that your wordpress install looks at the correct db tables

Change:

    $table_prefix  = 'wp_';

To:

    $table_prefix  = 'fth_';

### Step 14
Add the following line to wp-config

    define('POSTGRES_HOST', "host.docker.internal");

### Step 15
Next we need to install node on our system.

     curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.8/install.sh | bash
     ##Either close and re-open your terminal OR run the command below
     export NVM_DIR="$HOME/.nvm"
     [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"  # This sources nvm
     [ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"  # This sources nvm bash_completion
     ##Make sure 8.7 is installed
     nvm install 8.7
     ##Set up the site directory to use that version
     nvm use
     ##Install node modules
     npm install
     ## Build gulp   
     npx gulp dev

### Step 16
Access fatherly at localhost:8000 or whichever port you have set in your docker at step 5.

### Step 17 
To set up xdebug using vscode

1. Install PHP Debug extension
2. Debug > Add Configuration
3. Select PHP
4. You will see a "launch.json" file replace it with the following:
    ```
    {
      // Use IntelliSense to learn about possible attributes.
      // Hover to view descriptions of existing attributes.
      // For more information, visit: https://go.microsoft.com/fwlink/?linkid=830387
      "version": "0.2.0",
      "configurations": [
        {
          "name": "Listen for XDebug",
          "type": "php",
          "request": "launch",
          "port": 9899,
          "pathMappings": {
            "/var/www/html": "${workspaceFolder}",  //localhost/test
          },
        },
      ]
    }
    ```