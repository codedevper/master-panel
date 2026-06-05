## Master Panel
- **Laravel Jetstream
- **Laravel Reverb
- **Laravel Pulse
- **Laravel Telescope
- **Laravel Horizon

## Install Server
```bash
curl -fsSL https://raw.githubusercontent.com/codedevper/master-scripts/master/new_debian.sh | bash

git clone https://github.com/codedevper/master-panel.git ./panel

cd panel

composer setup

php artisan migrate:fresh --seed

php artisan reverb:install
php artisan reverb:start --debug --host=0.0.0.0 --port=9600

php artisan pulse:check
php artisan pulse:work
php artisan pulse:restart

php artisan horizon

php artisan horizon:pause
php artisan horizon:continue
php artisan horizon:pause-supervisor supervisor-1
php artisan horizon:continue-supervisor supervisor-1

php artisan horizon:status
php artisan horizon:supervisor-status supervisor-1

php artisan horizon:terminate
php artisan horizon:listen --poll

sudo cp /home/debian/panel/supervisor/conf.d/laravel-reverb.conf /etc/supervisor/conf.d/laravel-reverb.conf
sudo rm /etc/supervisor/conf.d/laravel-reverb.conf
sudo cp -a /home/debian/panel/supervisor/conf.d/. /etc/supervisor/conf.d/
sudo rm -rf /etc/supervisor/conf.d/*

sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl status

sudo supervisorctl start laravel-reverb
sudo supervisorctl start all
sudo supervisorctl stop all
sudo supervisorctl restart all

pkill -f hardhat

php vendor/bin/envoy init localhost

php vendor/bin/envoy run deploy-panel --domain=127.0.0.1
php vendor/bin/envoy run delete-panel
php vendor/bin/envoy run deploy-backend --domain=localhost --dbpass=password --admin_user=admin --admin_password=password --admin_email=admin@email.com
php vendor/bin/envoy run delete-backend
php vendor/bin/envoy run deploy-html --domain=127.0.0.1
php vendor/bin/envoy run delete-html

php artisan boost:update
```

## Upgrade to Pro
```bash
wp plugin install \
woo-wallet \
buddypress \
wc-frontend-manager \
wc-multivendor-marketplace \
wc-multivendor-membership \
wcfm-marketplace-rest-api \
--activate
```

## Tips
```bash
mysql -u root -p
ALTER USER 'root'@'localhost' IDENTIFIED BY 'password';
FLUSH PRIVILEGES;
```