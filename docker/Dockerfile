FROM php:5.6-apache

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    sudo \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libmcrypt-dev \
    nano \
    zip \
    unzip \
    nano


# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mysqli mbstring exif pcntl bcmath gd xml json soap sockets zip mcrypt

# Restart apache and serve
RUN service apache2 restart
# No limit for resources
RUN echo "memory_limit=-1" > $PHP_INI_DIR/conf.d/memory-limit.ini
RUN echo "max_execution_time=0" > $PHP_INI_DIR/conf.d/max-execution-time.ini
RUN echo "upload_max_filesize=10000M" > $PHP_INI_DIR/conf.d/upload-max-filesize.ini
RUN echo "post_max_size=10000M" > $PHP_INI_DIR/conf.d/post-max-size.ini
# Session lifetime 
RUN echo "session.gc_maxlifetime=2700" > $PHP_INI_DIR/conf.d/session-gc-maxlifetime.ini