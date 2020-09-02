<?php

require __DIR__ . '/../vendor/autoload.php';

class TestClass
{
	public function testA($a)
	{
		print __CLASS__ . ' - TEST A';
	}

	public function testB($a)
	{
		print __CLASS__ . ' - TEST B';
	}
}

$registry = new \Peavent\Registry();

$eventName = 'my_first_event';
$event = $registry->make($eventName);

$key = 0;

print PHP_EOL . PHP_EOL . str_repeat('-', 40) . PHP_EOL . 'Attaching params and callables' . PHP_EOL . str_repeat('-', 25) . PHP_EOL;
$event
	->setParams([
		'hello world'
	])
	->attach(function ($a) {
		print $a;
	})
	->attach(function ($a) {
		print 'also A, should be deleted';
	}, null, $key)
	->attach([new TestClass(), 'testA'])
	->attach([new TestClass(), 'testB']);

print PHP_EOL . PHP_EOL . str_repeat('-', 40) . PHP_EOL . 'First run, run everything' . PHP_EOL . str_repeat('-', 25) . PHP_EOL;
print 'Output:' . PHP_EOL;
$event->run();

print PHP_EOL . PHP_EOL . str_repeat('-', 40) . PHP_EOL . 'Running again, removing key' . PHP_EOL . str_repeat('-', 25) . PHP_EOL;
print 'Output:' . PHP_EOL;

$event->detach($key)->run();

print PHP_EOL . PHP_EOL . str_repeat('-', 40) . PHP_EOL . 'Running again, removing class' . PHP_EOL . str_repeat('-', 25) . PHP_EOL;
print 'Output:' . PHP_EOL;

$event->detach(TestClass::class)->run();

try {
	$registry->drop($eventName)->raise($eventName);
} catch (Exception $e) {
	print 'Expected exception: ' . $e->getMessage() . PHP_EOL;
	print 'Correct: ' . ($e->getMessage() === 'Event "my_first_event" does not exist' ? 'true' : 'false') . PHP_EOL;
}