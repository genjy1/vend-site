#!bash

BACKUP_HOST="89.22.150.26"
BACKUP_PORT="3567"
BACKUP_USER="backupsrv"
CURRENTTIME=`date +%s`
PROJECT_FOLDER="/var/www/vend-shop.com"

if [ ! -d /mnt/backfs ]
then 
	mkdir /mnt/backfs
fi

sshfs $BACKUP_USER@$BACKUP_HOST:/home/$BACKUP_USER/ /mnt/backfs -p $BACKUP_PORT

if [  -f $PROJECT_FOLDER/sql/vend_db.sql ]
then 
	rm $PROJECT_FOLDER/sql/vend_db.sql
fi

mysqldump -u root -h localhost vend_db > $PROJECT_FOLDER/sql/vend_db.sql

cd $PROJECT_FOLDER

tar -czv -f  /mnt/backfs/vend-shop-site-$CURRENTTIME.tar.gz *

umount /mnt/backfs

