<?php

class LoggerConfiguration
{
	public static function configure($logger)
	{
		$logger->getMonolog()->pushProcessor(function ($record)
		{
			$record['extra']['info'] = __FILE__;
			return $record;
		});
	}
}
