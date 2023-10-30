FROM php:8.1-cli

COPY ./php.ini /usr/local/etc/php/php.ini

RUN apt-get update && apt-get install -y libyaml-dev \
    && pecl install yaml \
    && echo "extension=yaml.so" > /usr/local/etc/php/conf.d/ext-yaml.ini \
    && docker-php-ext-enable yaml \
	&& apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

WORKDIR /

COPY ./entrypoint.sh /entrypoint.sh
COPY ./compose_generator.php /compose_generator.php
COPY ./docker-compose.yml /docker-compose.yml
COPY ./builder.cfg.json /builder.cfg.json

RUN chmod +x /entrypoint.sh
RUN chmod +x /compose_generator.php

ENTRYPOINT ["/entrypoint.sh"]
