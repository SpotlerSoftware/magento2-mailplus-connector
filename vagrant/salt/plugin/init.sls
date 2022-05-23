add-token:
  file.replace:
    - name: /var/www/html/auth.json
    - flags: DOTALL
    - pattern: \}([^}]+)$
    - repl: ', "github-oauth": {
                    "github.com": "{{ salt['pillar.get']('github:personal_access_token') }}"
                }
            }'

allow-plugins:
  file.replace:
    - name: /var/www/html/composer.json
    - flags: DOTALL
    - pattern: \}([^}]+)$
    - repl: ', "config": {
                     "allow-plugins": {
                         "magento/*": true,
                         "laminas/*": true,
                         "cweagans/composer-patches": true
                     }
                 }
             }'

get-mailplus-connector:
  cmd.run:
    - name: '(cd /var/www/html && composer config repositories.mailplus vcs https://github.com/SpotlerSoftware/magento2-mailplus-connector)'
    - require:
      - add-token
      - allow-plugins

require-mailplus-connector:
  cmd.run:
    - name: '(cd /var/www/html && composer require mailplus/mailplus-connector)'
    - require:
      - get-mailplus-connector

enable-mailplus-connector:
  cmd.run:
    - name: '(cd /var/www/html && bin/magento module:enable MailPlus_MailPlus)'
    - require:
      - require-mailplus-connector

upgrade-mailplus-connector:
  cmd.run:
    - name: '(cd /var/www/html && php bin/magento setup:upgrade)'
    - require:
      - enable-mailplus-connector

setup-mailplus-connector:
  cmd.run:
    - name: '(cd /var/www/html && php bin/magento setup:di:compile)'
    - require:
      - upgrade-mailplus-connector

