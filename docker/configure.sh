#!/bin/bash
# This script is responsible for generating random passwords for your database
# and setting up your environment files
# You should only ever run this script once.

if ! [ -n "$BASH_VERSION" ];then
    echo "this is not bash, calling self with bash....";
    SCRIPT=$(readlink -f "$0")
    /bin/bash $SCRIPT
    exit;
fi

if [ -d $HOME/polr-data ]; then
    echo "Unfortunately, it appears you already have polr data in $HOME/polr-data."
    echo "This script sets new passwords, which would make the database inaccessible."
    echo "If you wish to proceed, please delete that directory and re-run this script."
    exit 1
fi


# Execute everything from a relative point of where
# this script is stored.
DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
cd $DIR

# Copy the template file and use it as-is
cp docker-compose.yml.tmpl docker-compose.yml

# Generate password and replace if found
# Repeating this once already done does nothing
# which is a good thing.
ROOT_PASSWORD=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1)
POLR_USER_PASSWORD=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1)

# If there is no .env file, then we assume we have not copied
# .env.setup to .env yet.
echo "creating the polr .env file"
cp $DIR/../.env.setup $DIR/.env

FILEPATH="$DIR/.env"
SEARCH="# DB_CONNECTION=mysql"
REPLACE="DB_CONNECTION=mysql"
sed -i "s;$SEARCH;$REPLACE;" $FILEPATH

# update the db hostname
SEARCH="# DB_HOST=localhost"
REPLACE="DB_HOST=db"
sed -i "s;$SEARCH;$REPLACE;" $FILEPATH

# uncomment the db port
SEARCH="# DB_PORT=3306"
REPLACE="DB_PORT=3306"
sed -i "s;$SEARCH;$REPLACE;" $FILEPATH

# set the database name
SEARCH="# DB_DATABASE=homestead"
REPLACE="DB_DATABASE=polr"
sed -i "s;$SEARCH;$REPLACE;" $FILEPATH

# set the database user
SEARCH="# DB_USERNAME=homestead"
REPLACE="DB_USERNAME=polr"
sed -i "s;$SEARCH;$REPLACE;" $FILEPATH

# set the database password
SEARCH="# DB_PASSWORD=secret"
REPLACE="DB_PASSWORD=$POLR_USER_PASSWORD"
sed -i "s;$SEARCH;$REPLACE;" $FILEPATH


echo "Creating the .env.mysql file"

echo "MYSQL_DATABASE=polr
MYSQL_PASSWORD=$POLR_USER_PASSWORD
MYSQL_ROOT_PASSWORD=$ROOT_PASSWORD
MYSQL_USER=polr
" > $DIR/.env.mysql

echo "done!"
