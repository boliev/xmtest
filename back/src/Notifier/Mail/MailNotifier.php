<?php

namespace App\Notifier\Mail;

use App\Company\CompanyDTO;
use App\Notifier\NotifierInterface;
use App\Quotation\QuotationsFetchRequest;
use Swift_Mailer;
use Swift_Message;

class MailNotifier implements NotifierInterface
{
    private Swift_Mailer $mailer;
    private string $from;

    public function __construct(Swift_Mailer $mailer, string $from)
    {
        $this->mailer = $mailer;
        $this->from = $from;
    }

    public function notifyAboutSuccessRequest(string $address, CompanyDTO $company, QuotationsFetchRequest $request): void
    {
        $message = (new Swift_Message($company->getName()))
            ->setFrom($this->from)
            ->setTo($address)
            ->setBody(
                sprintf('From %s to %s',
                    $request->getStartDate()->format('Y-m-d'),
                    $request->getEndDate()->format('Y-m-d'),
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}
