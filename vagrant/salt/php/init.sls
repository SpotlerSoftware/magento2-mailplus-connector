php:
  pkg.installed:
      - pkgs:
        - php
        - php-mysql
        - php-mcrypt
        - php-curl
        - php-xml
        - php-intl
        - php-gd
        - php-cli
        - php-json
        - php-mbstring
        - php-zip
        - php-soap
# Uncomment to enable xdebug. Not recommended when using Composer to install magento
#        - php-xdebug

# Uncomment to enable xdebug. Not recommended when using Composer to install magento
#/etc/php/7.0/apache2/conf.d/20-xdebug.ini:
#  file.managed:
#    - source: salt://php/xdebug.ini

php5enmod mcrypt:
  cmd.run:
    - onlyif: type php5enmod
    - require:
      - pkg: php

php5enmod json:
  cmd.run:
    - onlyif: type php5enmod
    - require:
      - pkg: php
