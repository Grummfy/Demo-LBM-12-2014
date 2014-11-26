<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::get('log', function()
{
	return <<<LOG
<ul>
	<li><a href="/log/1">Simple log</a></li>
	<li><a href="/log/2">Callback logger</a></li>
	<li><a href="/log/3">Playing with monolog handler</a></li>
	<li><a href="/log/4">And formatters</a></li>
	<li><a href="/log/5">And processors</a></li>
</ul>
LOG;
;
});

Route::get('log/1', function()
{
	Log::debug('test logger debug');
	Log::info('test logger info');
	Log::notice('test logger notice');
	Log::warning('test logger warning');
	Log::error('test logger error');
	Log::alert('test logger alert');
	Log::emergency('test logger emergency');

	Log::info('Log message', array('context' => 'Other helpful information'));

	return 'Hello logger';
});

Route::get('log/2', function()
{
	Log::listen(function($level, $message, $context)
	{
		if ($level == \Psr\Log\LogLevel::INFO)
		{
			// can be helpfull to send an email
			// or specific response
			// or add debug trace
			// /!\ avoid infinite loop ...
			Log::debug('Debug from callback');
		}
		// App::abort(500);
	});

	Log::info(__FILE__ . '#' . __LINE__);

	return 'Hello logger Callback';
});

Route::get('log/3', function()
{
	/* @var \Monolog\Logger $monolog */
	$monolog = Log::getMonolog();

	// handle error (there is a faster way with laravel)
	// $monolog->pushHandler(new \Monolog\Handler\ErrorLogHandler());

	// display error to std output
	$monolog->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout'));
	// display error to stde rror
	$monolog->pushHandler(new \Monolog\Handler\StreamHandler('php://stderr'));
	// SyslogHandler

	$monolog->pushHandler(new \Monolog\Handler\SlackHandler('xoxp-3097227698-3097227702-3095751773-759a87', '#general'));

	$monolog->pushHandler(new \Monolog\Handler\FirePHPHandler());

	// php artisan serve 2> errror.log 1> stdout.log

	Log::debug(__FILE__ . '#' . __LINE__);
	Log::critical(__FILE__ . '#' . __LINE__);

	// https://laravel-meetup.slack.com/messages/general/

	return '<hr />Hello logger<hr />';
});

Route::get('log/4', function()
{
	/* @var \Monolog\Logger $monolog */
	$monolog = Log::getMonolog();

	$format = '[%level_name%]%datetime% %channel%: %message%' . PHP_EOL;
	$monolog->getHandlers()[0]->setFormatter(new \Monolog\Formatter\LineFormatter($format));

	$monolog->pushHandler(new \Monolog\Handler\FirePHPHandler());

	$monolog->pushHandler(new EchoHandler());

	Log::debug(__FILE__ . '#' . __LINE__);
	Log::critical(__FILE__ . '#' . __LINE__);

	return '<hr />Hello logger<hr />';
});

Route::get('log/5', function()
{
	/* @var \Monolog\Logger $monolog */
	$monolog = Log::getMonolog();

	$handler = new \Monolog\Handler\FirePHPHandler();
	$monolog->pushHandler($handler);

	// adding processor
	$monolog->pushProcessor(new \Monolog\Processor\WebProcessor());
	$monolog->pushProcessor(new \Monolog\Processor\PsrLogMessageProcessor());
	$monolog->pushProcessor(new \Monolog\Processor\GitProcessor());

	// custom processors

	// processor for all handlers
	$monolog->pushProcessor(function ($record)
	{
		$record['extra']['load'] = sys_getloadavg();

		return $record;
	});

	// processor for firephp handler only
	$handler->pushProcessor(function ($record)
	{
		$record['extra']['load2'] = sys_getloadavg();

		return $record;
	});

	Log::debug(__FILE__ . '#' . __LINE__);
	Log::critical(__FILE__ . '#' . __LINE__);
	Log::info('My message with a context {data}', ['data' => 'Yihaaa']);

	return '<hr />Hello logger<hr />';
});
