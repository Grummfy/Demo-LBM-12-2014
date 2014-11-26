<?php

class EchoHandler extends \Monolog\Handler\AbstractProcessingHandler
{
	protected function getDefaultFormatter()
	{
		return new \Monolog\Formatter\HtmlFormatter();
	}

	protected function write(array $record)
	{
		echo $record['formatted'];
	}
}
