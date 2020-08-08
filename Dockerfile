FROM php:7.4-fpm

WORKDIR /application

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential locales \
    libzip-dev zip unzip \
    vim git curl wget rsync

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /application

# Copy existing application directory permissions
COPY --chown=www:www . /application

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
