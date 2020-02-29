<?php
/**
 *
 */

namespace OmniTools\Core\Api;

abstract class AbstractController
{
    protected $payload;
    protected $payloadRaw;

    /**
     *
     */
    public function getPayload(string $attribute)
    {
        $segments = explode('.', $attribute);
        $data = $this->payload;

        for ($i = 0; $i <= count($segments); ++$i) {

            $key = array_shift($segments);

            if (!array_key_exists($key, $data)) {
                throw new \Exception(sprintf('Parameter %s missing.', $attribute));
            }

            $data = $data[$key];
        }

        return $data;
    }

    /**
     *
     */
    public function getPayloadOptional(string $attribute)
    {
        $segments = explode('.', $attribute);
        $data = $this->payload;

        for ($i = 0; $i <= count($segments); ++$i) {

            $key = array_shift($segments);

            if (!array_key_exists($key, $data)) {
                return null;
            }

            $data = $data[$key];
        }

        return $data;
    }

    /**
     *
     */
    public function getPayloadRaw(): string
    {
        return $this->payloadRaw;
    }

    /**
     *
     */
    public function setPayload(array $payload): void
    {
        $this->payload = $payload;
    }

    /**
     *
     */
    public function setPayloadRaw(string $payload): void
    {
        $this->payloadRaw = $payload;
    }
}
