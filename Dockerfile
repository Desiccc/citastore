FROM php:8.2-apache-bookworm AS base

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install \
    pdo_pgsql \
    pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    xml \
    zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set document root to Laravel's public directory
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|/var/www/|/var/www/html/public|g' /etc/apache2/apache2.conf

# Allow .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js 22.x (required by Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Copy entire application
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Build frontend assets
RUN npm install && npm run build

# Create .env from example
RUN cp .env.example .env

# Generate app key if not set
RUN php artisan key:generate --force || true

# Storage setup
RUN php artisan storage:link --force || true

# Fix storage permissions for web server
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Create startup script
RUN cat > /usr/local/bin/start.sh << 'SCRIPT'
#!/usr/bin/env bash
set -e

echo "[STARTUP] Sanitizing env vars via PHP..." >&2
php -r '
$vars = getenv();
foreach ($vars as $k => $v) {
    if (is_string($v) && preg_match("/[\r\n\t]/", $v)) {
        fwrite(STDERR, "[STARTUP] DIRTY: $k contains CR/LF/TAB\n");
        putenv("$k=" . str_replace(["\r","\n","\t"], "", $v));
    }
}
$url = "http://0.0.0.0:" . (getenv("PORT") ?: "80");
putenv("APP_URL=$url");
fwrite(STDERR, "[STARTUP] APP_URL=$url\n");
fwrite(STDERR, "[STARTUP] Sanitization complete\n");
'

echo "[STARTUP] Starting Apache..." >&2

echo "[STARTUP] Running migrations..." >&2
php artisan migrate --force 2>&1 || echo "[STARTUP] Migration skipped" >&2

exec apache2-foreground
SCRIPT

RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]
