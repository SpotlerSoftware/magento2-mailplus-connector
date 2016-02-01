php:
  pkg.installed:
      - pkgs:
        - php5
        - php5-mysql
        - php5-mcrypt
        - php5-curl
        - php5-xsl
        - php5-intl
        - php5-gd
        - php5-cli
        - php5-json


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
