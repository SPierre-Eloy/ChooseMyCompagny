#!/bin/sh

echo "CREATE CONTAINERS"
echo "---------"
docker-compose up -d --force-recreate --remove-orphan
echo

echo "ENSURE SERVICES HAVE TIME TO START..."
echo "---------"
sleep 15

echo 'done.'
