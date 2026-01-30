FROM php:8.4-fpm

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    # build-essential \
    # libfreetype6-dev \
    # locales \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev

# Install php extensions of infrastructure
RUN docker-php-ext-install pdo pdo_mysql pgsql pdo_pgsql

# Install extensions
RUN docker-php-ext-install mbstring zip exif pcntl bcmath
# RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
# RUN docker-php-ext-install gd

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# USER $user

# Create system user to run Composer and Artisan Commands
# RUN useradd -G www-data,root -u "$uid" -d "/home/$user" $user
# RUN mkdir -p /home/$user/.composer && \
    # chown -R $user:$user /home/$user

# Copy existing application directory contents
COPY . /var/www

RUN composer install

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
