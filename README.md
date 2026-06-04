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
```
