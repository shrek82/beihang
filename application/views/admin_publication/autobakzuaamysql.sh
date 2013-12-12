#!/bin/sh
rq=`date +%Y%m%d`
oldfile=`date +%Y%m%d --date='14 days ago'`
rm -rf /home/bakdata/zuaa$oldfile.tar.gz
tar zcvf /home/bakdata/zuaa$rq.tar.gz /var/lib/mysql/zuaa
scp /home/bakdata/zuaa$rq.tar.gz root@10.10.6.240:/usr/zuaaMysqlBackup/zuaaBak.tar.gz
