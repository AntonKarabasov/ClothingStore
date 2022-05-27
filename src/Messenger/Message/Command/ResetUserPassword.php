<?php

namespace App\Messenger\Message\Command;

class ResetUserPassword
{
	/**
	 * @var string
	 */
	private string $email;

	public function __construct(string $email)
	{
		$this->email = $email;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}
}