FROM php:8.4-cli
RUN docker-php-ext-install -j2 pdo_mysql
WORKDIR /app
CMD ["php", "-S", "0.0.0.0:8000", "-t", "/app"]
