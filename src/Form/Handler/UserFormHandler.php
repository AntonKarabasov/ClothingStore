<?php

namespace App\Form\Handler;

use App\Entity\User;
use App\Utils\Manager\UserManager;
use Symfony\Component\Form\Form;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFormHandler
{
	/**
	 * @var UserManager
	 */
	private UserManager $userManager;

	/**
	 * @var UserPasswordHasherInterface
	 */
	private UserPasswordHasherInterface $userPasswordHasher;

	public function __construct(UserManager $userManager, UserPasswordHasherInterface $userPasswordHasher)
	{
		$this->userManager = $userManager;
		$this->userPasswordHasher = $userPasswordHasher;
	}

	/**
	 * @param Form $form
	 *
	 * @return User
	 */
	public function processEditForm(Form $form): User
	{
		$plainPassword = $form->get('plainPassword')->getData();
		/** @var User $user */
		$user = $form->getData();

		if ($plainPassword) {
			$encodedPassword = $this->userPasswordHasher->hashPassword($user, $plainPassword);
			$user->setPassword($encodedPassword);
		}

		$this->userManager->save($user);

		return $user;
	}
}