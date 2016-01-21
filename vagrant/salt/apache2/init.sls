sudo a2enmod rewrite:
  cmd.run:
    - unless: test -f /etc/apache2/mods-enabled/rewrite.load
    - require:
      - pkg: apache2

apache2:
  pkg.installed:
    - pkgs:
      - apache2: '2.4.7-1ubuntu4.8'
  service.running:
      - name: apache2
      - watch:
            - pkg: apache2
            - cmd: sudo a2enmod rewrite
            - cmd: php

