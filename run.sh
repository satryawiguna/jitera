#!/bin/bash

if [ "$1" = "--service" -o "$1" = "-s" ]; then
    if [ "$2" != "" ]; then
        docker exec -t -i "$2" bash -c "composer install"
        docker exec -t -i "$2" bash -c ". ~/.nvm/nvm.sh && npm install"
        docker exec -t -i "$2" bash -c "php artisan key:generate"
        docker exec -t -i "$2" bash -c "php artisan optimize"
    fi
fi
