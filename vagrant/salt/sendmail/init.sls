sendmail:
  pkg.installed:
    - pkgs:
      - sendmail

setup-mail-host:
  file.append:
    - name: /etc/mail/sendmail.mc
    - text:
      - define(`SMART_HOST', `[m1.mailplus.test]')dnl
      - FEATURE(`nullclient', `m1.mailplus.test')dnl
    - require:
      - sendmail

restart-sendmail-service:
  cmd.run:
    - name: 'sudo systemctl restart sendmail.service'
    - require:
      - setup-mail-host