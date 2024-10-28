<?php

namespace App\Service;

use App\Form\FileMailerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class FileMailerService
{
    private $mailer;
    public function __construct(MailerInterface $mailer) {
        $this->mailer = $mailer;
    }
    public function sendEmail($frommail, $recevemail, $title, $context, $file=null): bool
    {
            $email = (new Email())
                ->from(addresses: $frommail)
                ->to($recevemail)
                ->subject($title)
                ->text($context);
            $email->getHeaders()->addTextHeader("X-Transport","automatsntp");

            if ($file !== null) {
                $email->attachFromPath($file->getPathname(), $file->getClientOriginalName());
            }
            try {
                // Send the email using the configured mailer
                $this->mailer->send($email);
                return true;
            }
            catch (\Exception $e) {
                return false;
            }
    }
}
