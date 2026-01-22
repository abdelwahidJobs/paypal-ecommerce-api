#!/bin/bash
set -e

echo "🚀 Laravel Setup Starting..."

# Wait for database to be ready
sleep 5

# Run migrations automatically
php artisan migrate --force || echo "⚠️ Migration failed, continuing..."

# Cache for production
php artisan config:cache || true
php artisan route:cache || true

echo "✅ Laravel Ready!"

# Start Apache
exec "$@"