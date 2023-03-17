FROM --platform=$TARGETPLATFORM php:8.2.3-cli AS application
ARG TARGETPLATFORM
ARG TARGETARCH
ARG BUILDPLATFORM
ARG BUILDARCH

# Install required packages
RUN apt-get update && apt-get install -y --no-install-recommends \
    default-mysql-client \
    gnupg \
    zip \
    unzip \
    imagemagick \
    libmagick++-dev \
    libmagickwand-dev \
    libfreetype6-dev \
    libjpeg-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libpq-dev \
    libonig-dev \
    libssl-dev \
    libwebp-dev \
    libxml2 \
    libxml2-dev \
    libxpm-dev \
    libzip-dev \
    openssl \
    postgresql \
    postgresql-contrib \
    libbz2-dev \
    libcurl4 \
    libcurl4-openssl-dev \
    libpcre3-dev \
    libxslt1-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Additional PHP extensions and configuration
RUN docker-php-ext-configure gd --with-jpeg --with-freetype --with-xpm --with-webp \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install bz2 \
    && docker-php-ext-install exif \
    && docker-php-ext-install iconv \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install opcache \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pgsql \
    && docker-php-ext-install pdo \
    && docker-php-ext-install pdo_pgsql \
    && docker-php-ext-install zip \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install intl \
    && docker-php-ext-install xsl \
    && pecl install imagick \
    && docker-php-ext-enable imagick

# Add composer
COPY --from=composer:2.5.4 /usr/bin/composer /usr/bin/composer

# Standard production config
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Custom php config
COPY docker/idp/php.ini $PHP_INI_DIR/conf.d/

# Install redis
RUN pecl install redis && docker-php-ext-enable redis

# Install oauth
RUN pecl install oauth && docker-php-ext-enable oauth

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Update bash profile
COPY ./docker/.profile /root/.profile
COPY ./docker/.profile /root/.bashrc
