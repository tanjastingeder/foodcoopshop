#!/usr/bin/env bash

# Email mock
sudo apt-get install -y -qq postfix  
sudo service postfix stop
smtp-sink -d "%d.%H.%M.%S" localhost:2500 1000 &
echo 'sendmail_path = "/usr/sbin/sendmail -t -i "' > $(php --ini|grep -m 1 "ini files in:"|cut -d ":" -f 2)/sendmail.ini

# MySQL Setup 
mysql --version
mysql -e 'CREATE DATABASE foodcoopshop_test;'
mysql foodcoopshop_test < ../config/sql/_installation/clean-db-structure.sql
mysql foodcoopshop_test < ../tests/config/sql/test-db-data.sql

# Composer
composer install --optimize-autoloader

# Use Travis config.
cp ../config/travis/* ../config/

# NPM
npm --prefix ../webroot install ../webroot

# Assets
bash ../bin/cake asset_compress build

# Apache Install, runs as www-data:www-data.
sudo apt-get install apache2 libapache2-mod-fastcgi

# Enable php-fpm, runs as travis:travis.
sudo cp ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.conf.default ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.conf

# For PHP7 inclusion of additional config files works.
sudo cp ../config/travis/php7.conf ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.d/
echo "cgi.fix_pathinfo = 1" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
~/.phpenv/versions/$(phpenv version-name)/sbin/php-fpm

# Disable XDebug to speed up Composer and test suites.
phpenv config-rm xdebug.ini

# PHP Debug
php --version

# Configure Apache virtual hosts.
sudo cp -f ../config/travis/apache /etc/apache2/sites-available/www.foodcoopshop.test.conf
sudo sed -e "s?%TRAVIS_BUILD_DIR%?$(pwd)?g" --in-place /etc/apache2/sites-available/www.foodcoopshop.test.conf

# Disable default Apache site.
sudo a2dissite 000-default

# Enable Apache test site.
sudo a2ensite www.foodcoopshop.test.conf

# Enable Apache modules.
sudo a2enmod rewrite actions fastcgi alias

# File modes
sudo chown -R travis:travis $TRAVIS_BUILD_DIR
sudo chmod o+rx /home/travis

# Enable Apache service.
sudo service apache2 restart

# Print Error Log
if [[ -f /var/log/apache2/www.foodcoopshop.test_error.log ]]; then
	sudo cat /var/log/apache2/www.foodcoopshop.test_error.log
fi
