**Amburan Reservation**
Configuration steps
 - Install related packages:  composer install
 ```sh
$ composer install
 ```
 -  Migrate tables and seed groups and roles
 ```sh
$ php artisan migrate:fresh --seed
 ```
