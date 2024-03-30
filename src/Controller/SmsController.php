<?php

namespace App\Controller;

use App\Entity\Sms;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ImapService;

class SmsController extends AbstractController
{
    #[Route('/api/fetch-sms', name: 'fetch_sms')]
    public function fetchEmails(ImapService $imapService,EntityManagerInterface $entityManager): Response
    {
        $messages = $imapService->getMessages();

        foreach ($messages as $message) {
            $sms = new Sms(); 

            $messageContent = $message->textHtml;

            $sender = $recipient = $content = $dateString = '';

            $text = strip_tags($message->textHtml);

            $lines = explode("\n", $text);

            foreach ($lines as $line) {

                if (strpos($line, 'Nadawca:') !== false) {
                    $startSender = strpos($line, 'Nadawca:') + strlen('Nadawca:');
                    $endSender = strpos($line, 'Odbiorca:');
                    $sender = trim(substr($line, $startSender, $endSender - $startSender));
                }
                
                if (strpos($line, 'Odbiorca:') !== false) {
                    $startRecipient = strpos($line, 'Odbiorca:') + strlen('Odbiorca:');
                    $recipient = trim(substr($line, $startRecipient));
                }
    
            
                if (strpos($line, 'Treść odebranej wiadomości:') !== false) {
                    $startContent = strpos($line, 'Treść odebranej wiadomości:') + strlen('Treść odebranej wiadomości:');
                    $content = trim(substr($line, $startContent));
                }
    
                if (strpos($line, 'Data:') !== false) {
                    $startDate = strpos($line, 'Data:') + strlen('Data:');
                    $dateString = trim(substr($line, $startDate));
                }
            }

            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $dateString);

            $existingMessage = $entityManager->getRepository(Sms::class)->findOneBy(['add_date' => $date]);

            if (!$existingMessage) {

                $sms->setSender($sender);
                $sms->setRecipient($recipient);
                $sms->setContentOfMessage($content);
                $sms->setAddDate($date );

                $entityManager->persist($sms);
            }
        }

        $entityManager->flush();

        return new Response();
    }
}
