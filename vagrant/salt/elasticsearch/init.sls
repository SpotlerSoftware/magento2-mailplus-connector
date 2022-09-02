get_elasticsearch:
  cmd.run:
    - name: |
        wget -qO - https://artifacts.elastic.co/GPG-KEY-elasticsearch | sudo gpg --dearmor -o /usr/share/keyrings/elasticsearch-keyring.gpg
        echo "deb [signed-by=/usr/share/keyrings/elasticsearch-keyring.gpg] https://artifacts.elastic.co/packages/8.x/apt stable main" | sudo tee /etc/apt/sources.list.d/elastic-8.x.list
        sudo apt-get update && sudo apt-get install elasticsearch
    - cwd: /root/
    - unless: dpkg -l elasticsearch*

elasticsearch:
  pkg.installed:
    - pkgs:
      - apt-transport-https
      - apache2
      - elasticsearch
  service.running:
    - enable: True
    - name: elasticsearch
    - watch:
      - pkg: elasticsearch
  require:
    - cmd: get_elasticsearch

enable-proxy:
  cmd.run:
    - name: sudo a2enmod proxy_http
    - cwd: /root/

/etc/elasticsearch/elasticsearch.yml:
  file.replace:
    - pattern: 'xpack\.security\.enabled\: true'
    - repl: 'xpack.security.enabled: false'
    - watch_in:
        - service: elasticsearch