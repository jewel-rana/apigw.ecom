#!/bin/bash
set -e

echo "Deploying application to dev server"
echo dev-admin

pushd /var/www/html/apigw.prokash.io

git checkout .
git pull origin master
yes | php8.2  /usr/local/bin/composer install
php8.2 artisan migrate
php8.2 artisan module:migrate

sudo chmod -R 777 storage
sudo chmod -R 777 bootstrap/cache
sudo php8.2 artisan route:clear
sudo php8.2 artisan view:clear
sudo php8.2 artisan cache:clear
sudo php8.2 artisan config:clear
sudo php8.2 artisan optimize:clear
sudo php8.2 artisan queue:restart

popd
sudo chown -Rf nginx:kartat /var/www/html/apigw.prokash.io
sudo chmod -Rf 770 /var/www/html/apigw.prokash.io
sudo chmod -Rf 777 /var/wwww/html/apigw.prokash.io/storage
sudo chmod 660 /var/www/html/apigw.prokash.io/storage/oauth-p*
sudo chmod -Rf 777 /var/www/html/apigw.prokash.io/bootstrap/cache

#sudo systemctl reload php8.2-fpm
#sudo nginx -s reload


# Exit maintenance mode
#php artisan up
echo "Application successfully deployed!"
