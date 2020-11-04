### Amburan Reservation
***Configuration steps***
 - Install related packages:
 ```sh
$ composer install
 ```
 -  Migrate tables and seed groups and roles
 ```sh
$ php artisan migrate:fresh --seed
 ```

 ```sh
 nohup php artisan queue:work --daemon > app/storage/logs/laravel.log &
 ```
