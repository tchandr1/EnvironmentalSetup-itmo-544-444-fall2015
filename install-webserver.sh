#!/bin/bash


echo "Installing apache2,php5 curl,mysql-client,imagick"
sudo apt-get update -y
sudo apt-get install -y apache2 git php5 php5-curl mysql-client curl php5-mysql
sudo apt-get install php5 php5-imagick

git clone https://github.com/tchandr1/EnvironmentalSetup-itmo-544-444-fall2015.git  

echo "Move required files to var/www/html"
    
sudo mv ./EnvironmentalSetup-itmo-544-444-fall2015/images /var/www/html/
sudo mv ./EnvironmentalSetup-itmo-544-444-fall2015/index.html /var/www/html/
sudo mv ./EnvironmentalSetup-itmo-544-444-fall2015/*.php /var/www/html/
sudo mv ./EnvironmentalSetup-itmo-544-444-fall2015/css /var/www/html/
sudo mv ./EnvironmentalSetup-itmo-544-444-fall2015/js /var/www/html/

curl -sS https://getcomposer.org/installer | sudo php &> /tmp/getcomposer.txt

sudo php composer.phar require aws/aws-sdk-php &> /tmp/runcomposer.txt

sudo mv vendor /var/www/html &> /tmp/movevendor.txt

sudo php /var/www/html/setup.php &> /tmp/database-setup.txt

echo "Hello! I am Thanusha Chandrahasa. Course:ITMO544-444, MiniProject1"








