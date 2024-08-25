cp ./src/.env.example ./src/.env && 
docker compose up -d && 
docker compose run composer install && 
docker compose run artisan key:generate && 
docker compose run artisan migrate:refresh --seed && 
docker compose run artisan test && 
docker compose run artisan l5-swagger:generate