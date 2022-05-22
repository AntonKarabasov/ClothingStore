<?php

namespace App\Utils\Mailer;

use App\Utils\Mailer\DTO\MailerOptions;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerSender
{

	/**
	 * @var MailerInterface
	 */
	private MailerInterface $mailer;

	/**
	 * @var LoggerInterface
	 */
	private LoggerInterface $logger;

	public function __construct(MailerInterface $mailer, LoggerInterface $logger)
	{
		$this->mailer = $mailer;
		$this->logger = $logger;
	}


	/**
	 * @param MailerOptions $mailerOptions
	 *
	 * @return Email
	 */
	public function sendTemplatedEmail(MailerOptions $mailerOptions)
	{
		$email = (new TemplatedEmail())
			->to($mailerOptions->getRecipient())
			->subject($mailerOptions->getSubject())
			->htmlTemplate($mailerOptions->getHtmlTemplate())
			->context($mailerOptions->getContext());

		if ($mailerOptions->getCc()) {
			$email->cc($mailerOptions->getCc());
		}

		try {
			$this->mailer->send($email);
		} catch (TransportExceptionInterface $exception) {
			$this->logger->critical($mailerOptions->getSubject(),[
				'errorText' =>$exception->getTraceAsString()
			]);

			$systemMailerOptions = new MailerOptions();
			$systemMailerOptions->setText($exception->getTraceAsString());

			$this->sendSystemEmail($systemMailerOptions);
		}

		return $email;
	}

	/**
	 * @param MailerOptions $mailerOptions
	 *
	 * @return Email
	 */
    private function sendSystemEmail(MailerOptions $mailerOptions)
    {
		$mailerOptions->setSubject('[Exception] An error occurred while sending the letter');
	    $mailerOptions->setRecipient('admin@ranked-choice.com');

		$email = (new Email())
			->to($mailerOptions->getRecipient())
			->subject($mailerOptions->getSubject())
			->text($mailerOptions->getText());

	    try {
		    $this->mailer->send($email);
	    } catch (TransportExceptionInterface $exception) {
		    $this->logger->critical($mailerOptions->getSubject(), [
			    'errorText' => $exception->getTraceAsString()
		    ]);
	    }

		return $email;
    }
}