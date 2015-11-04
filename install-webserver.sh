#!/bin/bash

echo "SSH in ubuntu" 
ssh ubuntu@ec2-52-26-56-48.us-west-2.compute.amazonaws.com -i ./itmo544-444-fall2015-surface-laptop.pem

sudo apt-get update -y
sudo apt-get install -y apache2 git php5 php5-curl mysql-client curl php5-mysql

curl -sS https://getcomposer.org/installer | php

sudo php composer.phar require aws/aws-sdk-php


git clone https://github.com/tchandr1/EnvironmentalSetup-itmo-544-444-fall2015.git  

echo "Moverequired files to var/www/html"
    
sudo mv ./EnvironmentalSetup-itmo-544-444-fall2015/images /var/www/html/images
sudo mv ./EnvironmentalSetup-itmo-544-444-fall2015/index.html /var/www/html/
sudo mv ./EnvironmentalSetup-itmo-544-444-fall2015/*.php /var/www/html/

sudo mv vendor /var/www/html

sudo php /var/www/html/setup.php

echo "Hello! I am Thanusha Chandrahasa. Course:ITMO544-444, MiniProject1"








