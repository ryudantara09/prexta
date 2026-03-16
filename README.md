# Prexta

## Requirements

- Docker installed and running
- PHP, Composer, and Node.js installed

## Install (first time)

```bash
composer run setup
```

> Then set `DB_HOST=127.0.0.1` in your `.env` file.

## Run

```bash
./vendor/bin/sail up -d mysql
composer run dev
```

---

## Deploy with Docker (production)

This packages the entire app into a self-contained Docker image. The client only needs Docker.

### 1. Create `Dockerfile` at the project root

```dockerfile
FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache nginx nodejs npm curl libpng-dev oniguruma-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies (no dev)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install JS dependencies and build assets
RUN npm ci && npm run build && rm -rf node_modules

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Copy nginx config
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Copy entrypoint
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
```

---

### 2. Create `docker/entrypoint.sh`

```bash
#!/bin/sh
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP-FPM and nginx
php-fpm -D
nginx -g "daemon off;"
```

---

### 3. Create `docker/nginx.conf`

```nginx
events {}
http {
    include mime.types;
    server {
        listen 80;
        root /var/www/html/public;
        index index.php;

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location ~ \.php$ {
            fastcgi_pass 127.0.0.1:9000;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include fastcgi_params;
        }
    }
}
```

---

### 4. Create `docker-compose.yml`

```yaml
services:
  app:
    build: .
    ports:
      - "80:80"
    environment:
      APP_ENV: production
      APP_KEY: base64:GENERATE_WITH_php_artisan_key_generate
      DB_CONNECTION: mysql
      DB_HOST: mysql
      DB_PORT: 3306
      DB_DATABASE: prexta
      DB_USERNAME: prexta
      DB_PASSWORD: secret
    depends_on:
      - mysql

  mysql:
    image: mysql:8.4
    environment:
      MYSQL_DATABASE: prexta
      MYSQL_USER: prexta
      MYSQL_PASSWORD: secret
      MYSQL_ROOT_PASSWORD: secret
    volumes:
      - db_data:/var/lib/mysql

volumes:
  db_data:
```

---

### 5. Build and run

```bash
docker compose up --build
```

The app will be available at **http://localhost**.

> Generate a fresh `APP_KEY` with `php artisan key:generate --show` and paste it into the `docker-compose.yml`.
