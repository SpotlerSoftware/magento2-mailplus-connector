debconf-utils:
  pkg.installed

mysql-setup:
  debconf.set:
    - name: mysql-server
    - data:
        'mysql-server/root_password': {'type': 'password', 'value': 'root' }
        'mysql-server/root_password_again': {'type': 'password', 'value': 'root' }
    - require:
      - pkg: debconf-utils

mysql-server:
  pkg.installed:
    - pkgs:
      -  mysql-server-5.6: '5.6.27-0ubuntu0.14.04.1'
    - require:
      - debconf: mysql-setup

mysql:
  service.running:
    - require:
      - pkg: mysql-server
    - watch:
      - pkg: mysql-server

mysql-database:
  cmd.run:
    - name: "mysql -u root -proot -e 'create database magento2'"