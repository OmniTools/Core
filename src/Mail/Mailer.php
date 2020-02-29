<?php
/**
 *
 */

namespace OmniTools\Core\Mail;

class Mailer
{
    protected $defaultFrom;
    protected $mailer;

    /**
     *
     */
    public function __construct($host, $username, $password, $port = 25, $encryption = null)
    {
        // Create the Transport
        $transport = new \Swift_SmtpTransport($host, $port);
        $transport->setUsername($username);
        $transport->setPassword($password);

        // Create the Mailer using your created Transport
        $this->mailer = new \Swift_Mailer($transport);
    }

    /**
     *
     */
    public function send(Message $message): void
    {
        $html = $message->getBody();

        $xmessage = new \Swift_Message($message->getSubject());
        $xmessage->setFrom(($message->getFrom() ?? [ $this->defaultFrom]));
        $xmessage->setTo($message->getTo());
        $xmessage->setBody(strip_tags($html));
        $xmessage->addPart($html, 'text/html');

        $result = $this->mailer->send($xmessage);
    }

    /**
     *
     */
    public function setDefaultFrom(string $address): void
    {
        $this->defaultFrom = $address;
    }
}
