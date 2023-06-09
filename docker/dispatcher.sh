#!/usr/bin/env bash

case $1 in
    'shell')
        /bin/bash
        ;;
    'install')
        php -d memory_limit=-1 /usr/local/bin/composer install --no-interaction --ansi
        ;;
    'update')
        php -d memory_limit=-1 /usr/local/bin/composer update --no-interaction --ansi
        ;;
    *)
        symfony serve --port=8000
        ;;
esac