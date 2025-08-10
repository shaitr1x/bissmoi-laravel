
FROM composer:2.7 AS build

WORKDIR /app

COPY . .

# Installe Node.js et npm
RUN apt-get update && apt-get install -y nodejs npm

RUN composer install --no-dev --optimize-autoloader
RUN cp .env.example .env || true
RUN php artisan key:generate
RUN npm install && npm run build

FROM php:8.2-cli

WORKDIR /app

COPY --from=build /app /app

RUN apt-get update && apt-get install -y libpq-dev git unzip && docker-php-ext-install pdo pdo_pgsql

EXPOSE 10000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
