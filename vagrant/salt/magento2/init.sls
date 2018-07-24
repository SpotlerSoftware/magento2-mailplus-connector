get-composer:
  cmd.run:
    - require:
      - pkg: php
    - name: 'curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer'
    - unless: test -f /usr/local/bin/composer
    - cwd: /root/


/var/www/html/index.html:
  file.absent

set-composer-keys:
  cmd.run:
    - require:
      - cmd: get-composer
      - cmd: magento2-community-edition
    - name: composer config http-basic.repo.magento.com 96fce2b01f0952d09208515bdccb1b77 bac7e70f3b3e5c783698de607f7c234b
    - cwd: /var/www/html
    
magento2-community-edition:
  cmd.run:
    - cwd: /var/www/html
    - name: |
        composer config -g http-basic.repo.magento.com 96fce2b01f0952d09208515bdccb1b77 bac7e70f3b3e5c783698de607f7c234b
        sudo composer -v create-project --prefer-dist -s dev --repository-url=https://nexus.mailplus.nl/satis/magento/ magento/project-community-edition /var/www/html/ 2.2.*
    - require:
      - cmd: get-composer
      - pkg: git
      - file: /var/www/html/index.html
    - unless: test -f /var/www/html/magento2/composer.lock

  file.directory:
    - name: '/var/www/html'
    - file_mode: 777
    - dir_mode: 777
    - user: www-data
    - group: www-data
    - recurse:
      - mode
      - user
      - group

magento-rights:
  file.directory:
    - name: '/var/www/html'
    - file_mode: 777
    - dir_mode: 777
    - user: www-data
    - group: www-data
    - recurse:
      - mode
      - user
      - group
    - watch:
       - cmd: magento-install
       - cmd: magento-upgrade

magento-cli:
  cmd.run:
    - name: 'ln -s /var/www/html/bin/magento /usr/local/bin/magento'
    - require:
           - file: magento2-community-edition
    - unless: test -f /usr/local/bin/magento

magento-install:
  cmd.run:
    - name: 'php /var/www/html/bin/magento setup:install --use-rewrites=1 --backend-frontname=admin
    --base-url=http://magento.local:9080/ --admin-firstname=dev --admin-lastname=dev --admin-email=test@mailplus.com --admin-user=admin --admin-password=Admin1234 --db-password=root --db-prefix=x_'
    - user: www-data
    - require:
          - cmd: magento-cli
    - unless: test -f /var/www/html/var/cache

magento-sample-data:
  cmd.run:
      - name: 'php bin/magento sampledata:deploy'
      - cwd: /var/www/html
      - unless: test -d /var/www/html/vendor/magento/module-bundle-sample-data
      - require:
        - cmd: magento-install

magento-upgrade:
  cmd.wait:
    - name: php bin/magento setup:upgrade
    - cwd: /var/www/html
    - watch:
      - cmd: magento-sample-data

magento-static:
  cmd.run:
    - name: 'php /var/www/html/bin/magento setup:static-content:deploy'
    - user: www-data
    - require:
          - cmd: magento-install
          - cmd: magento-sample-data
