<?php

require __DIR__ . '/../vendor/autoload.php';

$registry = new \Peavent\Registry();

$eventName = 'my_first_event';
$event = $registry->make($eventName);

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

$registry->drop($eventName)->raise($eventName);