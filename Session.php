<?php
namespace Pandora3\Libs\Session;

use Pandora3\Core\Interfaces\SessionInterface;

/**
 * Class Session
 * @package Pandora3\Libs\Session
 */
class Session implements SessionInterface {

	/** @var string $id */
	protected $id;

	/**
	 * @param string|null $id
	 */
	public function __construct($id = null) {
		if ($id === null) {
			session_start(); // todo: check already started
			$id = session_id();
		}
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * @return static
	 */
	public static function continue(): self {
		return new static();
	}

	/**
	 * @param string $id
	 * @return static
	 */
	public static function new(string $id): self {
		// self::startNewSession($sessionId)
		return new static();
	}

	protected static function saveCurrentSession(): void {
		if (session_status() !== PHP_SESSION_NONE) {
			session_write_close();
		}
	}

	protected function setup(): void {
		if (session_id() !== $this->id) {
			self::saveCurrentSession();
			session_id($this->id);
			session_start();
		}
	}

	/* public function getProperties(): array {
		$this->setup();
		return $_SESSION;
	} */

	/**
	 * @param string $property
	 * @return mixed
	 */
	public function get(string $property) {
		$this->setup();
		return $_SESSION[$property] ?? null;
	}

	/**
	 * @param string $property
	 * @param mixed $value
	 */
	public function set(string $property, $value): void {
		$this->setup();
		$_SESSION[$property] = $value;
	}

	/* *
	 * @param string $property
	 * @param mixed $value
	 * @return bool
	 * /
	public function isset(string $property, $value): bool {
		$this->setup();
		return isset($_SESSION[$property]);
	} */

	/**
	 * @param string $property
	 */
	public function clear(string $property): void {
		$this->setup();
		unset($_SESSION[$property]);
	}

}
