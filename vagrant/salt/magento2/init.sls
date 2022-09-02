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
        - cmd: downgrade-composer
        - cmd: magento2-community-edition
    - name: composer config http-basic.repo.magento.com 96fce2b01f0952d09208515bdccb1b77 bac7e70f3b3e5c783698de607f7c234b
    - cwd: /var/www/html

# As of Composer 2.2.0, the allow-plugins option adds a layer of security allowing you to restrict which Composer
# plugins are able to execute code during a Composer run.
# Composer 2.0.12 added support for the new GitHub token format.
downgrade-composer:
  cmd.run:
    - name: sudo composer self-update 2.0.12

# Magento 2.4.* doesn't support MariaDB 10.5 (yet)
# https://github.com/magento/magento2/issues/31109
override-composer:
  file.replace:
    - name: /var/www/html/app/etc/di.xml
    - pattern: '<item name="MariaDB-\(10\.2-10\.4\)" xsi\:type="string">\^10\\\.\[2-4\]\\\.<\/item>'
    - repl: <item name="MariaDB-(10.2-10.5)" xsi:type="string">^10\.[2-5]\.</item>
    - require:
      - magento2-community-edition

magento2-community-edition:
  cmd.run:
    - cwd: /var/www/html
    - name: |
        composer config -g http-basic.repo.magento.com 96fce2b01f0952d09208515bdccb1b77 bac7e70f3b3e5c783698de607f7c234b
        sudo composer -v create-project --prefer-dist -s dev --repository-url=https://nexus.mailplus.nl/satis/magento/ magento/project-community-edition /var/www/html/ 2.4.4
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

magento-cli:
  cmd.run:
    - name: 'ln -s /var/www/html/bin/magento /usr/local/bin/magento'
    - require:
        - file: magento2-community-edition
    - unless: test -f /usr/local/bin/magento

magento-install:
  cmd.run:
    - name: 'php /var/www/html/bin/magento setup:install --use-rewrites=1 --backend-frontname=admin
        --base-url=http://magento.local:9080/ --admin-firstname=dev --admin-lastname=dev --admin-email=test@mailplus.com
        --admin-user=admin --admin-password=Admin1234 --db-password=root --db-prefix=x_
        --search-engine=elasticsearch7 --elasticsearch-host="localhost" --elasticsearch-port=9200'
    - user: www-data
    - require:
        - cmd: magento-cli
        - override-composer
    - unless: test -f /var/www/html/var/cache
