#!bash

if [ ! -f /var/www/tools/projectloaded.lock ]; then
    echo "Waiting for MySQL Server"

	wait-for-it mysql:3306 -t 60 
    
    echo "Loading current database"

#if [ -f /var/www/sql/vend_back.sql ]  
#then
# mysql -u root -h mysql --password=opencart opencart < /var/www/sql/vend_back.sql 
#fi 
    
#if [ ! -f /var/www/sql/vend_back.sql ]  
#then 
#  mysql -u root -h mysql --password=opencart opencart < /var/www/sql/vend_db.sql 
#fi

    echo "generating new localhost ssl-cert"

	openssl req -x509 -out /etc/ssl/certs/ssl-cert-snakeoil.pem -keyout /etc/ssl/private/ssl-cert-snakeoil.key \
    -newkey rsa:2048 -nodes -sha256 \
    -subj '/CN=localhost' -extensions EXT -config <( \
     printf "[dn]\nCN=localhost\n[req]\ndistinguished_name = dn\n[EXT]\nsubjectAltName=DNS:localhost\nkeyUsage=digitalSignature\nextendedKeyUsage=serverAuth")

    echo "Configuring Apache2"

    ln /etc/apache2/mods-available/ssl.conf /etc/apache2/mods-enabled/ssl.conf
    ln /etc/apache2/mods-available/ssl.load /etc/apache2/mods-enabled/ssl.load
    ln /etc/apache2/mods-available/headers.load /etc/apache2/mods-enabled/headers.load
    ln /etc/apache2/mods-available/socache_shmcb.load /etc/apache2/mods-enabled/socache_shmcb.load

    ln /etc/apache2/sites-available/default-ssl.conf /etc/apache2/sites-enabled/default-ssl.conf

    echo "Copy opencart docker configuration"

    cp -f /var/www/tools/config.docker.php /var/www/html/config.php

    cp -f /var/www/tools/admin-config.docker.php /var/www/html/admin/config.php

    touch /var/www/tools/projectloaded.lock
fi

echo "Apache Start"

apache2-foreground
