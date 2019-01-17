FROM php:7.2-fpm

RUN apt-get update && apt-get install --no-install-recommends -y \
    libmcrypt-dev \
    libxml2-dev \
    librabbitmq-dev \
    libssh-dev \
    python python-pip \
    # required by Mcrypt Extension
    libicu-dev && \

    # required by Intl Extension
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN pecl install amqp apcu \
     && docker-php-ext-enable amqp
RUN echo "extension=apcu.so" > /usr/local/etc/php/conf.d/apcu.ini
RUN docker-php-ext-install -j$(nproc) opcache pdo pdo_mysql intl sockets bcmath mbstring soap
RUN echo "date.timezone = Europe/London" >> /usr/local/etc/php/php.ini

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN echo "memory_limit = 1G;" > /usr/local/etc/php/php.ini && \
    echo "error_reporting = E_ALL;" >> /usr/local/etc/php/php.ini && \
    echo "date.timezone = Europe/London" >> /usr/local/etc/php/php.ini

# xdebug
RUN pecl install xdebug && \
    docker-php-ext-enable xdebug
RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.remote_autostart=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.remote_connect_back=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.remote_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN apt-get update \
 && apt-get install --no-install-recommends -y libxml2-dev git \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install -j$(nproc) soap

RUN mkdir /var/www/symfony
WORKDIR /var/www/symfony
