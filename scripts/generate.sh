#!/bin/sh

host="localhost"
db="ssa"
usr="root"
pwd="mysql"

echo "DROP DATABASE IF EXISTS ssa; CREATE DATABASE ssa;" | /usr/bin/mysql -uroot -pmysql

perl insert_toc.pl $host $db $usr $pwd
