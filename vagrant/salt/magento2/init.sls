get-composer:
  cmd.run:
    - require:
      - pkg: php
    - name: 'curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer'
    - unless: test -f /usr/local/bin/composer
    - cwd: /root/


magento2-community-edition:
  cmd.run:
    - name: |
        composer config -g http-basic.repo.magento.com 96fce2b01f0952d09208515bdccb1b77 bac7e70f3b3e5c783698de607f7c234b
        composer create-project --repository-url=https://repo.magento.com/ magento/project-community-edition /var/www/html/magento2
    - require:
      - cmd: get-composer
      - pkg: git
    - unless: test -f /var/www/html/magento2/composer.lock

magento-rights:
  file.directory:
    - require:
       - cmd: magento2-community-edition
    - name: '/var/www/html/magento2'
    - user: www-data
    - group: www-data
    - file_mode: 777
    - dir_mode: 777
    - recurse:
      - user
      - group
      - mode

magento-cli:
  cmd.run:
    - name: 'ln -s /var/www/html/magento2/bin/magento /usr/local/bin/magento'
    - require:
      - file: magento-rights
    - unless: test -f /usr/local/bin/magento

magento-install:
  cmd.run:
    - name: 'php /var/www/html/magento2/bin/magento setup:install --admin-firstname=dev --admin-lastname=dev --admin-email=test@mailplus.com --admin-user=admin --admin-password=Admin1234 --db-password=root'
    - require:
          - cmd: magento-cli
    - unless: test -f /var/www/html/magento2/var/cache