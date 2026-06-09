## Master Panel
- **Laravel Jetstream
- **Laravel Reverb
- **Laravel Pulse
- **Laravel Telescope
- **Laravel Horizon

## Install Server
```bash
curl -fsSL https://raw.githubusercontent.com/codedevper/master-scripts/master/new_debian.sh | bash

git clone https://github.com/codedevper/latest.git ./panel

cd panel

composer setup

php artisan migrate:fresh --seed

php artisan reverb:start

php artisan pulse:check

php artisan horizon
```

## Admin User
username: admin@email.com
password: password
