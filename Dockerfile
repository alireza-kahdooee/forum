FROM php:8.0.11-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    vim \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user && \
chown -R $user:$user /home/$user
RUN mkdir /var/lib/mysql && touch /var/lib/mysql/mysqlLiteForTest.sqlite && \
chown -R $user:$user /var/lib/mysql/

# Set working directory
WORKDIR /var/www/html

USER $user
