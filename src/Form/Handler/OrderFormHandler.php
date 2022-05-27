<?php

namespace App\Form\Handler;

use App\Entity\Order;
use App\Utils\Manager\OrderManager;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdater;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class OrderFormHandler
{

	/**
	 * @var OrderManager
	 */
	private OrderManager $orderManager;

	/**
	 * @var PaginatorInterface
	 */
	private PaginatorInterface $paginator;

	/**
	 * @var FilterBuilderUpdater
	 */
	private FilterBuilderUpdater $filterBuilderUpdater;

	public function __construct(OrderManager $categoryManager, PaginatorInterface $paginator, FilterBuilderUpdater $filterBuilderUpdater)
	{
		$this->orderManager = $categoryManager;
		$this->paginator = $paginator;
		$this->filterBuilderUpdater = $filterBuilderUpdater;
	}

	public function processOrderFiltersForm(Request $request, FormInterface $filterForm)
	{
		$queryBuilder = $this->orderManager->getRepository()
			->createQueryBuilder('o')
			->leftJoin('o.owner', 'u')
			->where('o.isDeleted = :isDeleted')
			->setParameter('isDeleted', false);

		if ($filterForm->isSubmitted()) {
			$this->filterBuilderUpdater->addFilterConditions($filterForm, $queryBuilder);
		}

		return $this->paginator->paginate(
			$queryBuilder->getQuery(),
			$request->query->getInt('page', 1)
		);
	}

	/**
	 * @param Order $order
	 *
	 * @return Order
	 */
	public function processEditForm(Order $order): Order
	{
		$this->orderManager->recalculateOrderTotalPrice($order);
		$this->orderManager->save($order);

		return $order;
	}
}