#!/bin/sh
set -e

echo "Deploying application ..."

# Enter maintenance mode
(php artisan down ) || true
    # Update codebase
    # git fetch origin deploy
    # git reset --hard origin/deploy
    git fetch

    git checkout vendor/composer/autoload_classmap.php
    git checkout vendor/composer/autoload_static.php
    git checkout vendor/composer/InstalledVersions.php
    git checkout vendor/composer/installed.php
    git checkout vendor/composer/package-versions-deprecated/src/PackageVersions/Versions.php

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


# Exit maintenance mode
php artisan up

echo "Application deployed!"
