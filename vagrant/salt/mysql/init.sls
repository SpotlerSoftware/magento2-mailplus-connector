debconf for MariaDB:
  pkg.installed:
    - name: debconf-utils
  debconf.set:
    - name: mariadb-server-10.4
    - data:
        'mysql-server/root_password': {'type': 'password', 'value': 'root'}
        'mysql-server/root_password_again': {'type': 'password', 'value': 'root'}
    - require:
        - pkg: debconf for MariaDB

software-properties-common:
  pkg.installed

apt-key adv --fetch-keys https://mariadb.org/mariadb_release_signing_key.asc:
  cmd.run:
    - unless: 'apt-key list | grep mariadb'
    - order: first

mariadb:
  pkgrepo.managed:
    - humanname: mariadb
    - name: deb https://ftp.nluug.nl/db/mariadb/repo/10.4/debian buster main
    - file: /etc/apt/sources.list.d/mariadb.list
    - keyid: C74CD1D8
    - keyserver: keyserver.ubuntu.com
    - require_in:
        - pkg: mariadb
  pkg.installed:
    - pkgs:
        - mariadb-server
    - require:
        - debconf: debconf for MariaDB

mysql:
  service.running:
    - enable: True
    - require:
        - pkg: mariadb

mysql-database:
  cmd.run:
    - name: "mysql -u root -proot -e 'create database if not exists magento2'"

set-root-password:
  cmd.run:
    - name: "mysql -u root -proot mysql -e 'set password for \"root\"@localhost = password(\"{{ salt['pillar.get']('mysql:root_pw') }}\")'"
