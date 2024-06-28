#!bash

BACKUP_HOST="89.22.150.26"
BACKUP_PORT="3567"
BACKUP_USER="backupsrv"


LAST_BACKUP=`ssh $BACKUP_USER@$BACKUP_HOST "ls -p -t /home/backupsrv/*.tar.gz | head -1"`

if [ ! -d /tmp/vend-site ]
then 
	mkdir /tmp/vend-site
fi


scp $BACKUP_USER@$BACKUP_HOST:$LAST_BACKUP /tmp/vend-site.tar.gz 

tar -xv -f /tmp/vend-site.tar.gz -C /tmp/vend-site/

cp -f /tmp/vend-site/sql/vend_db.sql ./sql/vend_back.sql
cp -rn /tmp/vend-site/public_html/* ./public_html/ 

rm -r -f /tmp/vend-site
rm -f /tmp/vend-site.tar.gz

