<?php

namespace App\Service;

use PhpImap\Mailbox;

class ImapService
{
    private $mailbox;

    public function __construct()
    {
        $mailerDsn = $_ENV['MAILER_DSN'];

        $mailboxUrl = parse_url($mailerDsn);

        $host = $mailboxUrl['host'];
        $port = $mailboxUrl['port'];
        $username = $mailboxUrl['user'];
        $password = $mailboxUrl['pass'];
        
        $this->mailbox = new Mailbox(
            sprintf('{%s:%d/imap/ssl}INBOX', $host, $port),
            $username, 
            $password
        );
    }

    public function getMessages($limit = 10)
    {
        // Pobierz 10 najnowszych wiadomości
        $mailsIds = $this->mailbox->searchMailbox('ALL');
        $mails = [];

        foreach ($mailsIds as $mailId) {
            $mails[] = $this->mailbox->getMail($mailId);
        }

        return $mails;
    }
}