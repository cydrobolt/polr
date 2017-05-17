#!/bin/bash
if ! [ -n "$BASH_VERSION" ];then
    echo "this is not bash, calling self with bash....";
    SCRIPT=$(readlink -f "$0")
    /bin/bash $SCRIPT
    exit;
fi

DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

cd $HOME
mkdir -p polr-data/storage

# Remove any existing polr container
docker kill polr-container
docker rm polr-container

# Deploy the polr container on port 80
docker run -d \
-p 80:80 \
--name polr-container \
--volume $HOME/polr-data:/data \
polr
