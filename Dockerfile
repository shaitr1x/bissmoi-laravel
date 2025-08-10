

FROM php:8.2-cli AS build

WORKDIR /app

# Installe les dépendances système
RUN apt-get update && apt-get install -y git unzip libpq-dev nodejs npm

# Installe Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN cp .env.example .env || true
RUN php artisan key:generate
RUN npm install && npm run build

FROM php:8.2-cli

WORKDIR /app

COPY --from=build /app /app

RUN apt-get update && apt-get install -y libpq-dev git unzip && docker-php-ext-install pdo pdo_pgsql

EXPOSE 8080

CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8080
