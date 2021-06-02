#!/bin/bash
chmod -R 777 src/
mkdir src/storage/app/public/avatar
mkdir src/storage/app/public/ktp
mkdir src/storage/app/public/products
mv src/storage/logo.png src/storage/app/public/
mv src/storage/default.png src/storage/app/public/avatar/
mv src/storage/bank-logo/ src/storage/app/public/

docker-compose up -d --build
bash cmd.sh composer update
bash cmd.sh artisan key:generate
bash cmd.sh artisan cache:clear 
bash cmd.sh artisan config:clear
bash cmd.sh npm install
bash cmd.sh npm run production
docker-compose stop
docker-compose up -d
bash cmd.sh artisan migrate:fresh
bash cmd.sh artisan storage:link
bash migration_data/migrate.sh
chmod -R 777 src/
