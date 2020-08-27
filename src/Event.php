<?php

namespace Peavent;

use ArrayObject;

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
	 * Event constructor.
	 *
	 * @param string $name
	 */
	public function __construct(string $name)
	{
		parent::__construct();

		$this->name = $name;
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
		foreach ($this as $item) {
			call_user_func_array($item, $this->params);
		}

		return $this;
	}

	/**
	 * Attach a callable event
	 *
	 * @param callable $callable
	 * @param int $key Key in stack in case you may want to detach event
	 *
	 * @return $this
	 * @throws Exception
	 */
	public function attach($callable, int &$key = null): Event
	{
		if (!is_callable($callable)) {
			throw new Exception('Parameter is not callable');
		}

		$this[] = $callable;

		end($this);
		$key = key($this);

		return $this;
	}

	/**
	 * Detach a callable event
	 *
	 * @param int $key
	 *
	 * @return $this
	 */
	public function detach(int $key): Event
	{
		unset($this[$key]);

		return $this;
	}
}