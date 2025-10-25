#web: vendor/bin/heroku-php-apache2 public/
# release: php artisan migrate:fresh --force && php artisan db:seed --force
# Procfile

web: vendor/bin/heroku-php-apache2 public/
release: |
  php artisan cache:clear && \
  php artisan config:clear && \
  php artisan route:clear && \
  php artisan view:clear && \
 # php artisan event:clear && \
 # php artisan vendor:publish --provider="Encore\Admin\AdminServiceProvider" --tag=laravel-admin-assets --force --no-interaction && \
 # php artisan migrate --force --no-interaction && \
 # php artisan config:cache && \
 # php artisan route:cache && \
 # php artisan view:cache && \
 # php artisan db:seed --force --no-interaction

