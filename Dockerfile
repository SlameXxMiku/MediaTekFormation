FROM dunglas/frankenphp:php8.4-alpine

RUN install-php-extensions pdo_mysql intl opcache

COPY . /app
WORKDIR /app

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV DATABASE_URL="mysql://fake:fake@localhost:3306/fake"
ENV APP_ENV=prod

RUN composer install --no-dev --optimize-autoloader --no-scripts
RUN composer run-script post-install-cmd --no-interaction || true

RUN php bin/console cache:clear --env=prod || true
RUN php bin/console cache:warmup --env=prod || true
RUN php bin/console asset-map:compile --env=prod || true

# Rendre le dossier var writable au runtime
RUN chmod -R 777 /app/var

RUN printf '{\n\tauto_https off\n\tadmin off\n}\n:{$PORT} {\n\troot * /app/public\n\tphp_server\n}\n' > /etc/caddy/Caddyfile

CMD php bin/console security:hash-password admin --env=prod 2>&1; sleep 3600
