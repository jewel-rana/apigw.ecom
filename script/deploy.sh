#!/bin/bash
set -e

echo "Deploying application to dev server"
echo dev-admin

pushd /var/www/html/apigw.prokash.io

git checkout .
git pull origin master
yes | php composer install
php artisan migrate
php artisan module:migrate

sudo chmod -R 777 storage
sudo chmod -R 777 bootstrap/cache
sudo php artisan route:clear
sudo php artisan view:clear
sudo php artisan cache:clear
sudo php artisan config:clear
sudo php artisan optimize:clear
sudo php artisan queue:restart

popd
sudo chown -Rf nginx:kartat /var/www/html/apigw.prokash.io
sudo chmod -Rf 770 /var/www/html/apigw.prokash.io
sudo chmod -Rf 777 /var/wwww/html/apigw.prokash.io/storage
sudo chmod 660 /var/www/html/apigw.prokash.io/storage/oauth-p*
sudo chmod -Rf 777 /var/www/html/apigw.prokash.io/bootstrap/cache

#sudo systemctl reload php-fpm
#sudo nginx -s reload


# Exit maintenance mode
#php artisan up
echo "Application successfully deployed!"
