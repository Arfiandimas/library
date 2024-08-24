FROM php:8-fpm-alpine

# Set environment variables for UID and GID
ENV UID=1000
ENV GID=1000

# Create the application directory
RUN mkdir -p /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Remove the unnecessary 'dialout' group
RUN delgroup dialout

# Add a new system group and user for Laravel
RUN addgroup -g ${GID} --system laravel && \
    adduser -G laravel --system -D -s /bin/sh -u ${UID} laravel

# Configure PHP-FPM to use the 'laravel' user and group
RUN sed -i "s/user = www-data/user = laravel/g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i "s/group = www-data/group = laravel/g" /usr/local/etc/php-fpm.d/www.conf && \
    echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf

# Install necessary PHP extensions and bash
RUN docker-php-ext-install pdo pdo_mysql && \
    apk add --no-cache libzip-dev zip bash && \
    docker-php-ext-install zip

# Install Redis extension
RUN mkdir -p /usr/src/php/ext/redis && \
    curl -L https://github.com/phpredis/phpredis/archive/5.3.4.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 && \
    echo 'redis' >> /usr/src/php-available-exts && \
    docker-php-ext-install redis

# Set the default command to run PHP-FPM
CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
