web: vendor/bin/heroku-php-nginx -C nginx_app.conf /public
chmod -R 775 public/
cd public/ && ln -s ../storage/app/public public/storage && cd ..
php artisan storage:link
