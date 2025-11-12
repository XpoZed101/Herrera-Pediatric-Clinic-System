#!/bin/bash

# 1. Install PHP dependencies (Composer)
echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# 2. Install Node dependencies
echo "Installing Node dependencies..."
npm install

# 3. Run the front-end build (Vite/npm run build)
echo "Running front-end build..."
npm run build