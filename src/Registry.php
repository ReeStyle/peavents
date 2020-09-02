<?php

namespace Peavent;

use Exception;
use ArrayObject;

/**
 * Class Registry
 *
 * @package Peavent
 */
class Registry extends ArrayObject
{

	/**
	 * @var Registry
	 */
	private static $instance;

	/**
	 * Get the one and only instance
	 *
	 * @return Registry
	 */
	public static function instance(): Registry
	{
		return isset(self::$instance) ? self::instance() : self::$instance = new self;
	}

	/**
	 * Create event object
	 *
	 * @param string $ident
	 *
	 * @return Event
	 * @throws Exception
	 */
	public function make(string $ident): Event
	{
		if (!isset($this[$ident])){
			$this[$ident] = new Event($ident);
		} else {
			throw new Exception(sprintf('Event "%s" already exists', $ident));
		}

		return $this[$ident];
	}

	/**
	 * Retrieve event
	 *
	 * @param string $ident
	 *
	 * @return Event
	 * @throws Exception
	 */
	public function get(string $ident): Event
	{
		if (!isset($this[$ident])){
			throw new Exception(sprintf('Event "%s" does not yet exist', $ident));
		}

		return $this[$ident] = new Event($ident);
	}

	/**
	 * Lookup event
	 *
	 * @param string $ident
	 *
	 * @return $this
	 * @throws Exception
	 */
	public function raise(string $ident): Registry
	{
		if (isset($this[$ident])) {
			$this[$ident]->run();
		} else {
			throw new Exception(sprintf('Event "%s" does not exist', $ident));
		}

		return $this;
	}

	/**
	 * Raise all events - usually only done in test scenario's
	 *
	 * @return Registry
	 */
	public function raiseAll(): Registry
	{
		foreach ($this as $item) {
			$item->run();
		}

		return $this;
	}

	/**
	 * De-registers event and drops all events attached
	 *
	 * @param string $ident
	 *
	 * @return $this
	 */
	public function drop(string $ident): Registry
	{
		if (isset($this[$ident])) {
			unset($this[$ident]);
		}

		return $this;
	}
}
