#!/bin/sh
set -e

echo "-Change dir to"

cd /home/neighborsbackend/neighbors-backend-laravel/ && pwd

echo "-Deploying application ..."

echo "-Pulling GitHub"

su neighborsbackend -c echo "ok" && git pull

echo "-Docker process to migrates DB"

docker exec -it neighbors_bk_app php artisan migrate

echo "-Application deployed!"


