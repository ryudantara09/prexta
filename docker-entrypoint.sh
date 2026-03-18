#!/bin/bash
set -e

# Run caching for production
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Run database seeder to ensure initial setup (like admin user)
echo "Running database seeders..."
php artisan db:seed --force

# Pass control to the main container command (e.g., Apache)
exec "$@"
