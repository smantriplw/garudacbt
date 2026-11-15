FROM php:8.2-fpm-alpine

# Match the host user so the bind-mounted uploads/logs/cache stay writable.
ARG UID=1000
ARG GID=1000

RUN apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS libpng-dev libjpeg-turbo-dev freetype-dev libzip-dev icu-dev \
    && apk add --no-cache libpng libjpeg-turbo freetype libzip icu mariadb-client zip gzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" mysqli pdo_mysql gd zip intl calendar exif bcmath opcache \
    && apk del .build-deps \
    && rm -rf /tmp/* /var/cache/apk/*

RUN addgroup -g ${GID} app || true \
    && adduser -D -u ${UID} -G app app || true \
    && sed -i "s/^user = www-data/user = app/; s/^group = www-data/group = app/" /usr/local/etc/php-fpm.d/www.conf \
    && sed -i "s/^pm.max_children = .*/pm.max_children = 5/; s/^pm.start_servers = .*/pm.start_servers = 2/; s/^pm.min_spare_servers = .*/pm.min_spare_servers = 1/; s/^pm.max_spare_servers = .*/pm.max_spare_servers = 3/" /usr/local/etc/php-fpm.d/www.conf

COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/entrypoint.sh /usr/local/bin/app-entrypoint
RUN chmod +x /usr/local/bin/app-entrypoint

WORKDIR /var/www/html

ENTRYPOINT ["app-entrypoint"]
CMD ["php-fpm"]
