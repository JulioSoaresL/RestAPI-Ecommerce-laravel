FROM php:latest
LABEL authors="Julio Soares"

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libonig-dev \
    libxml2-dev

RUN docker-php-ext-install pdo_mysql mbstring

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

#ENTRYPOINT ["top", "-b"]