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

apache2:
  pkg.installed:
    - pkgs:
      - apache2
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
            - sls: php

