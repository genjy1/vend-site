#!bash

BACKUP_HOST="192.168.0.5"
BACKUP_PORT="22"
BACKUP_USER="backupsrv"

TMP_DIR="./tmp"


LAST_BACKUP=`ssh $BACKUP_USER@$BACKUP_HOST "ls -p -t /home/backupsrv/*.tar.gz | head -1"`


if [ ! -d $TMP_DIR/vend-site ]
then 
	mkdir $TMP_DIR/vend-site
fi

if [ -f $TMP_DIR/vend-site.tar.gz ] 
then
	rm -v -f $TMP_DIR/vend-site.tar.gz
fi


scp $BACKUP_USER@$BACKUP_HOST:$LAST_BACKUP $TMP_DIR/vend-site.tar.gz 

echo "Extracting..."

tar -x -v -f $TMP_DIR/vend-site.tar.gz -C $TMP_DIR/vend-site/

echo "Copy files..."

cp -f -u -v $TMP_DIR/vend-site/sql/vend_db.sql ./sql/vend_back.sql
cp -r -v -n $TMP_DIR/vend-site/public_html/* ./public_html/ 

echo "Clearing temp..."

rm -r -v -f $TMP_DIR/vend-site
rm -v -f $TMP_DIR/vend-site.tar.gz

