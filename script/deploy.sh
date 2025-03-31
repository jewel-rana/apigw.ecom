#!/bin/bash
set -e

echo "Deploying application to dev server"

pushd /var/www/html/apigw.prokash.io

git checkout .
git pull origin master
/usr/local/bin/composer install
sudo chmod -R 777 storage
sudo chmod -R 777 bootstrap/cache
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan optimize:clear
php artisan queue:restart

sudo chown -Rf nginx:nginx /var/www/html/apigw.prokash.io
sudo chmod -Rf 770 /var/www/html/apigw.prokash.io
sudo chmod -Rf 777 /var/wwww/html/apigw.prokash.io/storage
sudo chmod 660 /var/www/html/apigw.prokash.io/storage/oauth-p*
sudo chmod -Rf 777 /var/www/html/apigw.prokash.io/bootstrap/cache

#sudo systemctl reload php-fpm
#sudo nginx -s reload


# Exit maintenance mode
#php artisan up
echo "Application successfully deployed!"
