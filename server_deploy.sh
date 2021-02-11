#!/bin/sh
set -e

echo "Deploying application ..."

# Enter maintenance mode
(php artisan down ) || true
    # Update codebase
    # git fetch origin deploy
    # git reset --hard origin/deploy
    git fetch

    git pull
    # Install dependencies based on lock file
    composer update -n

    # Migrate database
    php artisan migrate --force
    php artisan route:cache
    php artisan route:clear
    php artisan cache:clear
    php artisan view:cache
    php artisan view:clear
    php artisan optimize
    php artisan l5-swagger:generate
    # Note: If you're using queue workers, this is the place to restart them.
    # ...
    php artisan l5-swagger:generate

    # Clear cache
    php artisan optimize

# Exit maintenance mode
php artisan up

php artisan l5-swagger:generate

echo "Application deployed!"
