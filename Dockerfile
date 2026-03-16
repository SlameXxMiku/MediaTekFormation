FROM dunglas/frankenphp:latest-php8.3

# Extensions PHP courantes pour Symfony
RUN install-php-extensions pdo_mysql intl opcache

# Copier le projet
COPY . /app
WORKDIR /app

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer les dépendances
RUN composer install --no-dev --optimize-autoloader

# Préparer Symfony pour la prod
RUN php bin/console cache:clear --env=prod
RUN php bin/console asset-map:compile --env=prod || true

# Caddyfile pour FrankenPHP
RUN echo '{\n\tauto_https off\n}\n:${PORT} {\n\troot * /app/public\n\tphp_server\n}' > /etc/caddy/Caddyfile

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
