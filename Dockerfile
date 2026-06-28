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
    nodejs \
    npm \
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

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Copy package files and install Node dependencies
COPY package.json package-lock.json ./
RUN npm install && npm run build

# Copy application code
COPY . .

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

# Sanitize env vars that may contain CR/LF/TAB from Railway injection
for var in APP_URL APP_KEY DB_HOST DB_DATABASE DB_USERNAME DB_PASSWORD; do
  val=$(printf "%s" "${!var}" | tr -d '\r\n\t' || true)
  export "$var"="$val"
done

echo "[STARTUP] Starting Apache..." >&2

# Run migrations (non-fatal)
php artisan migrate --force 2>&1 || echo "[STARTUP] Migration skipped" >&2

exec apache2-foreground
SCRIPT

RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]
