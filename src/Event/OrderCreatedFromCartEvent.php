<?php

namespace App\Event;

use App\Entity\Order;
use Symfony\Contracts\EventDispatcher\Event;


class OrderCreatedFromCartEvent extends Event
{
	/**
	 * @var Order
	 */
	private $order;

	public function __construct(Order $order)
	{
		$this->order = $order;
	}

	/**
	 * @return Order
	 */
	public function getOrder(): Order
	{
		return $this->order;
	}
}