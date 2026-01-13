
docker compose exec -u www-data backend composer install

docker compose exec -u www-data backend php bin/console migrations:migrate

docker compose exec -u www-data backend php bin/console migrations:generate