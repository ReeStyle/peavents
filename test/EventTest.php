<?php

require __DIR__ . '/../vendor/autoload.php';

$eventName = 'my_first_event';
$event = new \Peavent\Event($eventName);

$key = 0;

$event
	->setParams([
		'hello world'
	])
	->attach(function ($a) {
		print $a;
	})
	->attach(function ($a) {
		print 'also A, should be deleted';
	}, $key);

$event->run();

$event->detach($key)->run();
