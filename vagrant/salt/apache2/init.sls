sudo a2enmod rewrite:
  cmd.run:
    - unless: test -f /etc/apache2/mods-enabled/rewrite.load
    - require:
      - pkg: apache2

sudo a2enmod headers:
  cmd.run:
    - unless: test -f /etc/apache2/mods-enabled/headers.load
    - require:
      - pkg: apache2

sudo a2enmod proxy_http:
  cmd.run:
    - unless: test -f /etc/apache2/mods-enabled/proxy_http.load
    - require:
      - pkg: apache2

apache2:
  pkg.installed:
    - pkgs:
      - apache2
      - libapache2-mod-php
  file.managed:
    - name: /etc/apache2/apache2.conf
    - source:
          - salt://apache2/apache2.conf
  service.running:
      - name: apache2
      - watch:
            - pkg: apache2
            - file: apache2
            - cmd: sudo a2enmod rewrite
            - cmd: sudo a2enmod headers
            - cmd: sudo a2enmod proxy_http
            - sls: php

update-sites-available:
  file.line:
    - name: /etc/apache2/sites-available/000-default.conf
    - mode: insert
    - location: start
    - content: Listen 8080
    - unless: test grep Listen 8080

/etc/apache2/sites-available/000-default.conf:
  file.append:
    - text: |
        <VirtualHost *:8080>
          ProxyPass "/" "http://localhost:9200/"
          ProxyPassReverse "/" "http://localhost:9200/"
        </VirtualHost>
    - require:
      - update-sites-available
    - watch_in:
      - service: apache2
