#
# NOTE: THIS DOCKERFILE IS GENERATED VIA "apply-templates.sh"
#
# PLEASE DO NOT EDIT IT DIRECTLY.
#
FROM php:8.3.14-apache

ARG UID
ARG GID

RUN set -eux; \
    \
    savedAptMark="$(apt-mark showmanual)"; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libgmp-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libpq-dev \
        libzip-dev \
        $PHPIZE_DEPS \
    ; \
    docker-php-ext-configure gd \
        --with-freetype=/usr/include/ \
        --with-jpeg=/usr/include/ \
    ; \
    docker-php-ext-install -j"$(getconf _NPROCESSORS_ONLN)" \
        bcmath \
        mysqli \
        gd \
        gmp \
        intl \
        opcache \
        pdo_mysql \
        pdo_pgsql \
        pcntl \
        sockets \
        zip \
    ; \
    \
    pecl update-channels; \
    pecl install \
        redis \
        xdebug \
    ; \
    docker-php-ext-enable redis \
        xdebug \
    ; \
    rm -rf /tmp/pear ~/.pearrc; \
    apt-mark auto '.*' > /dev/null; \
    [ -z "$savedAptMark" ] || apt-mark manual $savedAptMark; \
    find /usr/local -type f -executable -exec ldd '{}' ';' \
        | awk '/=>/ { so = $(NF-1); if (index(so, "/usr/local/") == 1) { next }; gsub("^/(usr/)?", "", so); print so }' \
        | sort -u \
        | xargs -r dpkg-query --search \
        | cut -d: -f1 \
        | sort -u \
        | xargs -r apt-mark manual \
    ; \
    apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false; \
    rm -rf /var/lib/apt/lists/*; \
    \
    php --version

COPY ./config/xdebug.ini "${PHP_INI_DIR}/conf.d"

# Include composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer 

COPY ./apache/000-default.conf /etc/apache2/sites-enabled/000-default.conf

# Configure Apache to use SSL
COPY ./config/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN usermod -a -G www-data root    
RUN usermod -u $UID www-data
# cooment out in mac system
#RUN groupmod -g $GID www-data 

RUN chown -R www-data:www-data /var/www

RUN a2enmod rewrite

# иначе подобные ссылки /icons/* не будут работать
RUN rm /etc/apache2/mods-enabled/alias.conf

EXPOSE 80