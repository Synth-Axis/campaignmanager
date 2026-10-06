FROM node:24-bookworm-slim AS styles
WORKDIR /build
COPY package.json package-lock.json ./
RUN npm ci --include=dev
COPY tailwind.config.js postcss.config.js ./
COPY assets ./assets
COPY app ./app
COPY views ./views
COPY index.php ./
RUN npm run build

FROM php:8.3-apache-bookworm AS runtime
RUN apt-get update \
    && apt-get install -y --no-install-recommends libfreetype6-dev libjpeg62-turbo-dev libpng-dev libzip-dev libonig-dev unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" gd mbstring zip pdo_mysql opcache \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-progress --prefer-dist --optimize-autoloader
COPY app ./app
COPY config ./config
COPY views ./views
COPY assets ./assets
COPY index.php index1.html favicon.svg .htaccess ./
COPY --from=styles /build/dist ./dist
COPY docker/apache.conf /etc/apache2/conf-available/lynx-app.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/lynx-app.ini
COPY docker/start.sh /usr/local/bin/lynx-start
RUN a2enconf lynx-app \
    && chmod +x /usr/local/bin/lynx-start \
    && mkdir -p public/uploads storage/logs \
    && chown -R www-data:www-data public/uploads storage/logs
ENV APP_ENV=production
ENV PORT=8080
EXPOSE 8080
CMD ["/usr/local/bin/lynx-start"]
