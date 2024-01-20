#!/bin/bash

set -e

export DOCKER_DEFAULT_PLATFORM=linux/amd64

image="doelia/schtroumpf-traducteur:main"

docker login
docker build . -f .cloud/Dockerfile -t $image
docker push $image
