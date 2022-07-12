ARG PHP_VERSION=8.1

###########################################
# PHP dependencies
###########################################
FROM composer:latest AS vendor

LABEL maintainer="Ali Alizade <ali.alizade@outlook.com"

# Address relatively order by dockerfiles directory path
ARG SOURCE_DIR="api"

WORKDIR /var/www/html

COPY ${SOURCE_DIR}/composer* ./

RUN composer install \
  --no-dev \
  --no-interaction \
  --prefer-dist \
  --ignore-platform-reqs \
  --optimize-autoloader \
  --apcu-autoloader \
  --ansi \
  --no-scripts

###########################################
FROM php:${PHP_VERSION}-cli-bullseye

ARG WWWUSER=1000
ARG WWWGROUP=1000
ARG TZ=UTC

# Accepted values: app - horizon - scheduler
ARG CONTAINER_MODE=app

ARG APP_WITH_HORIZON=false
ARG APP_WITH_SCHEDULER=false

ENV DEBIAN_FRONTEND=noninteractive \
    TERM=xterm-color \
    CONTAINER_MODE=${CONTAINER_MODE} \
    APP_WITH_HORIZON=${APP_WITH_HORIZON} \
    APP_WITH_SCHEDULER=${APP_WITH_SCHEDULER}

ENV ROOT=/var/www/html
WORKDIR $ROOT

SHELL ["/bin/bash", "-eou", "pipefail", "-c"]

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
    && echo $TZ > /etc/timezone

RUN apt-get update; \
    apt-get upgrade -yqq; \
    pecl -q channel-update pecl.php.net; \
    apt-get install -yqq --no-install-recommends --show-progress \
          apt-utils \
          gnupg \
          gosu \
          git \
          curl \
          wget \
          libcurl4-openssl-dev \
          ca-certificates \
          supervisor \
          libmemcached-dev \
          libz-dev \
          libbrotli-dev \
          libpq-dev \
          libjpeg-dev \
          libpng-dev \
          libfreetype6-dev \
          libssl-dev \
          libwebp-dev \
          libmcrypt-dev \
          libonig-dev \
          libzip-dev zip unzip \
          libargon2-1 \
          libidn2-0 \
          libpcre2-8-0 \
          libpcre3 \
          libxml2 \
          libzstd1 \
          procps

###########################################
# pdo_mysql
###########################################

RUN docker-php-ext-install pdo_mysql;

###########################################
# zip
###########################################

RUN docker-php-ext-configure zip && docker-php-ext-install zip;

###########################################
# mbstring
###########################################

RUN docker-php-ext-install mbstring;

###########################################
# GD
###########################################

RUN docker-php-ext-configure gd \
            --prefix=/usr \
            --with-jpeg \
            --with-webp \
            --with-freetype \
    && docker-php-ext-install gd;

###########################################
# OPcache
###########################################

ARG INSTALL_OPCACHE=true

RUN if [ ${INSTALL_OPCACHE} = true ]; then \
      docker-php-ext-install opcache; \
  fi

###########################################
# PHP Redis
###########################################

ARG INSTALL_PHPREDIS=true

RUN if [ ${INSTALL_PHPREDIS} = true ]; then \
      pecl -q install -o -f redis \
      && rm -rf /tmp/pear \
      && docker-php-ext-enable redis; \
  fi

###########################################
# PCNTL
###########################################

ARG INSTALL_PCNTL=true

RUN if [ ${INSTALL_PCNTL} = true ]; then \
      docker-php-ext-install pcntl; \
  fi

###########################################
# BCMath
###########################################

ARG INSTALL_BCMATH=true

RUN if [ ${INSTALL_BCMATH} = true ]; then \
      docker-php-ext-install bcmath; \
  fi

###########################################
# OpenSwoole/Swoole extension
###########################################

ARG INSTALL_SWOOLE=true
ARG SERVER=openswoole

RUN if [ ${INSTALL_SWOOLE} = true ]; then \
      apt-get install -yqq --no-install-recommends --show-progress libc-ares-dev \
      && pecl -q install -o -f -D 'enable-openssl="yes" enable-http2="yes" enable-swoole-curl="yes" enable-mysqlnd="yes" enable-cares="yes"' ${SERVER} \
      && docker-php-ext-enable ${SERVER}; \
    fi

###########################################################################
# Human Language and Character Encoding Support
###########################################################################

ARG INSTALL_INTL=true

RUN if [ ${INSTALL_INTL} = true ]; then \
      apt-get install -yqq --no-install-recommends --show-progress zlib1g-dev libicu-dev g++ \
      && docker-php-ext-configure intl \
      && docker-php-ext-install intl; \
  fi

###########################################
# MySQL Client
###########################################

ARG INSTALL_MYSQL_CLIENT=true

RUN if [ ${INSTALL_MYSQL_CLIENT} = true ]; then \
      apt-get install -yqq --no-install-recommends --show-progress default-mysql-client; \
  fi


###########################################
# Laravel scheduler
###########################################

RUN if [ ${CONTAINER_MODE} = 'scheduler' ] || [ ${APP_WITH_SCHEDULER} = true ]; then \
      wget -q "https://github.com/aptible/supercronic/releases/download/v0.1.12/supercronic-linux-amd64" \
           -O /usr/bin/supercronic \
      && chmod +x /usr/bin/supercronic \
      && mkdir -p /etc/supercronic \
      && echo "*/1 * * * * su octane -c \"php ${ROOT}/artisan schedule:run --verbose --no-interaction\"" > /etc/supercronic/laravel; \
  fi

###########################################
ARG DOCKER_CONFIG_DIR="dockerfiles/php"

RUN groupadd --force -g $WWWGROUP octane \
    && useradd -ms /bin/bash --no-log-init --no-user-group -g $WWWGROUP -u $WWWUSER octane

RUN apt-get clean \
    && docker-php-source delete \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && rm /var/log/lastlog /var/log/faillog

COPY ${SOURCE_DIR}/ .
COPY --from=vendor ${ROOT}/vendor vendor

RUN mkdir -p \
  storage/framework/{sessions,views,cache} \
  storage/logs \
  bootstrap/cache \
  && chown -R octane:octane \
  storage \
  bootstrap/cache \
  && chmod -R ug+rwx storage bootstrap/cache

COPY ${DOCKER_CONFIG_DIR}/deployment/octane/supervisord* /etc/supervisor/conf.d/
COPY ${DOCKER_CONFIG_DIR}/deployment/octane/php.ini /usr/local/etc/php/conf.d/octane.ini
COPY ${DOCKER_CONFIG_DIR}/deployment/octane/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

RUN cat ${DOCKER_CONFIG_DIR}/deployment/octane/utilities.sh >> ~/.bashrc

COPY ${DOCKER_CONFIG_DIR}/deployment/octane/entrypoint.sh /
RUN chmod +x /entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/entrypoint.sh"]

HEALTHCHECK --start-period=5s --interval=2s --timeout=5s --retries=8 CMD php artisan octane:status || exit 1
