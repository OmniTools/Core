<?php
/**
 *
 */

namespace OmniTools\Core\View;

class Response
{
    protected $type = 'html';
    protected $file = null;
    protected $data = [];

    /**
     *
     */
    public function getData(): array
    {
        return $this->data ?? [];
    }

    /**
     *
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     *
     */
    public function setData(array $data): Response
    {
        $this->data = $data;

        return $this;
    }

    /**
     *
     */
    public function setFile(string $file): Response
    {
        $this->file = $file;

        return $this;
    }
}