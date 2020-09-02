<?php

namespace Peavent;

use ArrayObject;

/**
 * Class Event
 *
 * @package Peavent
 */
class Event extends ArrayObject
{

	/**
	 * @var
	 */
	private $name;

	/**
	 * @var array
	 */
	private $params = [];

	/**
	 * @var bool
	 */
	private $isCli = false;

	/**
	 * Event constructor.
	 *
	 * @param string $name
	 */
	public function __construct(string $name)
	{
		parent::__construct();

		$this->name = $name;

		$this->isCli = php_sapi_name() === 'cli';
	}

	/**
	 * Parameters to add when running event
	 *
	 * @param array $params
	 *
	 * @return $this
	 */
	public function setParams(array $params): Event
	{
		$this->params = $params;

		return $this;
	}

	/**
	 * Run stack
	 *
	 * @return $this
	 */
	public function run(): Event
	{
		$this->cliOut('');
		$this->cliOut(sprintf('Running \'%s\'', $this->getName()));

		foreach ($this as $pos => $item) {
			$this->cliOut(sprintf('Executing %s at position %s', $item['className'], $pos));
			call_user_func_array($item['callable'], $this->params);
		}

		return $this;
	}

	/**
	 * Attach a callable event
	 *
	 * @param callable $callable
	 * @param int|null $position
	 * @param int|null $key Key in stack in case you may want to detach event
	 *
	 * @return $this
	 * @throws Exception
	 */
	public function attach($callable, int $position = null, int &$key = null): Event
	{
		if (!is_callable($callable)) {
			throw new Exception('Parameter is not callable');
		}

		$class = $method = null;
		$isFunc = false;
		if (is_array($callable)) {
			if (is_object($callable[0])) {
				$class = get_class($callable[0]);
				$method = $callable[1];
			}
		} else {
			$isFunc = true;
		}

		$info = [
			'callable' => $callable,
			'className' => $class,
		];

		$this->cliOut(sprintf(!$isFunc ? 'Attaching %s::%s' : 'Attaching function', $class, $method));

		if (is_int($position)) {
			$this[$position] = $info;

			$key = $position;
		} else {
			$this[] = $info;

			/** @var array|Event $this */
			end($this);
			$key = key($this);
		}

		return $this;
	}

	/**
	 * Detach a callable event
	 *
	 * @param int|object $key If object or class name provided, will detach ALL
	 *
	 * @return $this
	 */
	public function detach($key): Event
	{
		if (is_object($key) || is_string($key)) {
			if (is_string($key)) {
				$class = $key;
			} else {
				$class = get_class($key);
			}

			foreach ($this as $pos => $item) {
				$this->cliOut($item['className']);
				if ($item['className'] === $class) {
					$this->cliOut(sprintf('Detach %s at position %s', $item['className'], $pos));
					unset($this[$pos]);
				}
			}
		} else {
			unset($this[$key]);
		}

		return $this;
	}

	/**
	 * @param string $line
	 */
	public function cliOut(string $line): void
	{
		if ($this->isCli) {
			print $line . PHP_EOL;
		}
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}
}
