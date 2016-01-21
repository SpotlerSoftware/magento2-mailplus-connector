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
  cmd.run:
    - name: |
        php5enmod mcrypt
        php5enmod json
