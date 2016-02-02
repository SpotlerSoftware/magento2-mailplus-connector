get-composer:
  cmd.run:
    - require:
      - pkg: php
    - name: 'curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer'
    - unless: test -f /usr/local/bin/composer
    - cwd: /root/


/var/www/html/index.html:
  file.absent


magento2-community-edition:
  cmd.run:
    - name: |
        composer config -g http-basic.repo.magento.com 96fce2b01f0952d09208515bdccb1b77 bac7e70f3b3e5c783698de607f7c234b
        sudo composer -v create-project --prefer-dist -s dev --repository-url=http://nexus.spotler.com/satis/magento/ magento/project-community-edition /var/www/html/
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
       - cmd: magento-static

magento-cli:
  cmd.run:
    - name: 'ln -s /var/www/html/bin/magento /usr/local/bin/magento'
    - require:
           - file: magento2-community-edition
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