#!/bin/bash

sudo apt-get update -y
sudo apt-get install -y apache2 git

git clone https://github.com/tchandr1/EnvironmentalSetup-itmo-544-444-fall2015.git

mv ./EnvironmentalSetup-itmo-544-444-fall2015/images /var/www/html/images
mv ./EnvironmentalSetup-itmo-544-444-fall2015/index.html /var/www/html/

echo "Hello! I am Thanusha Chandrahasa. Course:ITMO544-444, Week05Assignment" > /tmp/hello.txt

