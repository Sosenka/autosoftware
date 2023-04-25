FROM phpdockerio/php:8.2-fpm

WORKDIR /var/www

RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony && export PATH="usr/local/bin/.symfony/bin:$PATH"

ADD docker/dispatcher.sh /usr/bin/dispatcher.sh
RUN chmod +x /usr/bin/dispatcher.sh

# instalacja zależności
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git zip unzip memcached php8.2-memcached

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer


ENTRYPOINT [ "dispatcher.sh" ]
