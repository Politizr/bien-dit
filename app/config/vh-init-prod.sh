#!/bin/bash
read -p "URL: " url
read -p "URL admin: " urladmin

touch $url.conf
cat > $url.conf <<EOF
<VirtualHost *:80>
    ServerName $url
    ServerAlias $url
    Redirect permanent / https://$url/
</VirtualHost>

<VirtualHost *:443>
    Protocols h2 http/1.1

    ServerAdmin lionel.bouzonville@gmail.com
    ServerName $url
    ServerAlias $url

    SSLEngine on
    SSLCertificateKeyFile /etc/sslmate/$url.key
    SSLCertificateFile /etc/sslmate/$url.chained.crt

    # Recommended security settings from https://wiki.mozilla.org/Security/Server_Side_TLS
    # SSLProtocol all -SSLv2 -SSLv3
    # SSLCipherSuite ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-AES128-SHA256:ECDHE-RSA-AES128-SHA256:ECDHE-ECDSA-AES128-SHA:ECDHE-RSA-AES256-SHA384:ECDHE-RSA-AES128-SHA:ECDHE-ECDSA-AES256-SHA384:ECDHE-ECDSA-AES256-SHA:ECDHE-RSA-AES256-SHA:DHE-RSA-AES128-SHA256:DHE-RSA-AES128-SHA:DHE-RSA-AES256-SHA256:DHE-RSA-AES256-SHA:ECDHE-ECDSA-DES-CBC3-SHA:ECDHE-RSA-DES-CBC3-SHA:EDH-RSA-DES-CBC3-SHA:AES128-GCM-SHA256:AES256-GCM-SHA384:AES128-SHA256:AES256-SHA256:AES128-SHA:AES256-SHA:DES-CBC3-SHA:!DSS
    # SSLHonorCipherOrder on
    # SSLCompression off

    # Enable this if you want HSTS (recommended)
    Header add Strict-Transport-Security "max-age=15768000"

    DocumentRoot /var/www/$url/web
    <Directory /var/www/$url/web/>
        DirectoryIndex index.php

        Options FollowSymLinks MultiViews
        AllowOverride All
        Require all granted

        FallbackResource /index.php
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/error-$url.log

    # Possible values include: debug, info, notice, warn, error, crit,
    # alert, emerg.
    LogLevel warn

    CustomLog ${APACHE_LOG_DIR}/access-$url.log combined
</VirtualHost>
EOF

touch $urladmin.conf
cat > $urladmin.conf <<EOF
<VirtualHost *:80>
    ServerName $urladmin
    Redirect permanent / https://$urladmin/
</VirtualHost>

<VirtualHost *:443>
    ServerAdmin lionel.bouzonville@gmail.com
    ServerName $urladmin
    ServerAlias $urladmin

    SSLEngine on
    SSLCertificateKeyFile /etc/sslmate/$url.key
    SSLCertificateFile /etc/sslmate/$url.chained.crt

    DocumentRoot /var/www/$url/web
    <Directory />
        Options FollowSymLinks
        AllowOverride None
    </Directory>
    <Directory /var/www/$url/web/>
        Options FollowSymLinks MultiViews
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error-$urladmin.log

    # Possible values include: debug, info, notice, warn, error, crit,
    # alert, emerg.
    LogLevel warn

    CustomLog ${APACHE_LOG_DIR}/access-$urladmin.log combined
</VirtualHost>
EOF

a2ensite $url.conf
a2ensite $urladmin.conf
# /etc/init.d/apache2 reload

echo "*** VirtualHost $url & $urladmin initialisés ***"
