#!/bin/bash
if ! [ -n "$BASH_VERSION" ];then
    echo "this is not bash, calling self with bash....";
    SCRIPT=$(readlink -f "$0")
    /bin/bash $SCRIPT
    exit;
fi

# Execute everything from a relative point of where
# this script is stored.
DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
cd $DIR

mkdir -p $HOME/polr-data/storage

# Check the user has docker-compose
if ! [ -x "$(command -v docker-compose)" ]; then
  echo 'Error: docker-compose is not installed.' >&2
  echo 'On ubuntu you can install it with:' >&2
  echo 'sudo apt update && sudo apt install docker-compose -y' >&2
  exit 1
fi

# Spin down anything if already running...
docker-compose down

# Use docker-compose to spin up polr and the mysql container.
docker-compose up
