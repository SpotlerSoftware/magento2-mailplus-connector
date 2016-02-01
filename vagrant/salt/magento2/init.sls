magento-archive:
  archive.extracted:
    - name: /var/www/html
    - source: salt://magento2/Magento-CE-2.0.2-2016-01-28-02-24-15.tar.gz
    - archive_format: tar
    - if_missing: /var/www/html/bin
  file.directory:
    - name: '/var/www/html'
    - user: www-data
    - group: www-data
    - file_mode: 777
    - dir_mode: 777
    - recurse:
      - user
      - group
      - mode

magento-rights:
  file.directory:
    - name: '/var/www/html'
    - user: www-data
    - group: www-data
    - file_mode: 777
    - dir_mode: 777
    - recurse:
      - user
      - group
      - mode
    - watch:
       - cmd: magento-install
       - cmd: magento-static

magento-cli:
  cmd.run:
    - name: 'ln -s /var/www/html/bin/magento /usr/local/bin/magento'
    - require:
           - archive: magento-archive
    - unless: test -f /usr/local/bin/magento

magento-install:
  cmd.run:
    - name: 'php /var/www/html/bin/magento setup:install --use-rewrites=1 --backend-frontname=admin --base-url=http://magento.dev:8080/ --admin-firstname=dev --admin-lastname=dev --admin-email=test@mailplus.com --admin-user=admin --admin-password=Admin1234 --db-password=root'
    - user: www-data
    - require:
          - cmd: magento-cli
    - unless: test -f /var/www/html/var/cache

magento-static:
  cmd.run:
    - name: 'php /var/www/html/bin/magento setup:static-content:deploy'
    - user: www-data
    - require:
          - cmd: magento-install

/var/www/html/index.html:
  file.absent