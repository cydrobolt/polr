#!/bin/bash

# Remove any existing polr container
docker kill polr-container
docker rm polr-container

# Deploy the polr container on port 80
docker run -d \
-p 80:80 \
--name polr-container \
polr
