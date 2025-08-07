#!/bin/bash
set -e

echo "Waiting for database to be ready..."
until php bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do
    echo "Database not ready, waiting..."
    sleep 2
done

echo "Database is ready!"

echo "Checking migration status..."
php bin/console doctrine:query:sql "DROP TABLE IF EXISTS doctrine_migration_versions" || true

echo "Creating database schema..."
php bin/console doctrine:schema:create --no-interaction || true

echo "Marking migrations as executed..."
php bin/console doctrine:migrations:version --add --all --no-interaction || true

echo "Loading fixtures..."
php bin/console doctrine:fixtures:load --no-interaction

echo "Clearing cache..."
php bin/console cache:clear

echo "Starting Symfony server..."
exec "$@"