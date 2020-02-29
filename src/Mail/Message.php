<?php
/**
 *
 */

namespace OmniTools\Core\Mail;

class Message
{
    protected $subject;
    protected $to = [];
    protected $from;
    protected $body;

    /**
     *
     */
    public function __construct(string $subject)
    {
        $this->subject = $subject;
    }

    /**
     *
     */
    public function addTo(string $email): void
    {
        $this->to[] = $email;
    }

    /**
     *
     */
    public function clearTo(): void
    {
        $this->to = [];
    }

    /**
     *
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     *
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     *
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     *
     */
    public function getTo(): array
    {
        return $this->to;
    }

    /**
     *
     */
    public function setBody(string $html): void
    {
        $this->body = $html;
    }

    /**
     *
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }
}
