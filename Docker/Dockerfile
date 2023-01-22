# Pull php 8 version with FPM by Alpine to support Laravel 9
FROM php:8.1.9-fpm

# Environment
ENV NODE 18.13.0
ENV UID 1000
ENV USER jitera

# Replace shell with bash
RUN rm /bin/sh && ln -s /bin/bash /bin/sh

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    libzip-dev \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libpq-dev \
    nano

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-configure gd --with-jpeg=/usr/include/ --with-freetype=/usr/include/
RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd
#RUN pecl install -o -f redis &&  \
#    rm -rf /tmp/pear &&  \
#    docker-php-ext-enable redis

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/bin --filename=composer

# Create system user
RUN groupadd -g 1000 $USER
RUN useradd -ms /bin/bash -d /home/$USER -g $USER -G www-data,root $USER
RUN chown -R $USER:$USER /home/$USER

# Switch user to jitera
USER $USER

# Install nvm
RUN touch ~/.bashrc &&  \
    chmod +x ~/.bashrc
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.3/install.sh | bash
RUN . ~/.nvm/nvm.sh  \
    && source ~/.bashrc  \
    && nvm install node $NODE  \
    && nvm use default

# Set working directory
WORKDIR /var/www

# Copy existing application directory permissions
COPY --chown=$USER:$USER . /var/www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
