# Demo for Laravel Brussels Meetup
Event of the presentation : http://www.meetup.com/Laravel-Brussels/events/159804062/

## Launch it
* php artisan serve
* http://localhost:8000/log


## Change
* app/start/global.php : adding path to app/lib for autoloading
* app/routes.php : adding demo route to log
* app/config/log.php : configuration of logger (newly created file)
* bootstrap/start.php : adding log.setup

## Stuff to know
* vendor/laravel/framework/src/Illuminate/Log/LogServiceProvider.php : initialize all dispatching to logger
	* can be change in app/config/app.php to your own
* app/storage/logs/laravel.log is the path to the log
* app[log.setup] is a callback to configure the log
