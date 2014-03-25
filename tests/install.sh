#!/bin/bash

MYSQL_PREFIX=/usr/local/swan/smeta/opt/mysql/bin/mysql
MYSQL_RUN=/usr/local/swan/smeta/run/sw_mysql.sock

CMD="$MYSQL_PREFIX -uroot -S $MYSQL_RUN < db_desc_sf_unit.sql"
echo $CMD; 
eval $CMD;
