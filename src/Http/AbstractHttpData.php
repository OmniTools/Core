<?php
/**
 *
 */

namespace OmniTools\Core\Http;

abstract class AbstractHttpData
{
    protected $data = [];

    /**
     *
     */
    public function get(string $attribute)
    {
        return $this->data[$attribute] ?? null;
    }

    /**
     *
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     *
     */
    public function require(array $requirements): void
    {
        foreach ($requirements as $key => $attribute) {

            if (!is_array($attribute)) {

                if (empty($this->data[$attribute])) {
                    throw new \OmniTools\Core\Exception\InputMissing('Bitte alle Pflichtfelder ausfüllen.');
                }
            }
            else {

                foreach ($attribute as $attr) {
                    if (empty($this->data[$key][$attr])) {
                        throw new \OmniTools\Core\Exception\InputMissing('Bitte alle Pflichtfelder ausfüllen.');
                    }
                }
            }
        }
    }

    /**
     *
     */
    protected function setData(array $data): void
    {
        $this->data = $data;
    }
}