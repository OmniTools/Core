<?php
/**
 *
 */

namespace OmniTools\Core\View;

class MenuItem
{
    protected $href;
    protected $icon;
    protected $label;
    protected $title;
    protected $class;
    protected $attributes;

    /**
     *
     */
    public function __construct($label = null, $href = null, $icon = null, $class = null, $title = null, array $attributes = null)
    {
        $this->label = $label;
        $this->href = $href;
        $this->icon = $icon;
        $this->class = $class;
        $this->title = $title;
        $this->attributes = $attributes;
    }

    /**
     *
     */
    public function getAttributes(): array
    {
        if (empty($this->attributes)) {
            return [];
        }

        return $this->attributes;
    }

    /**
     *
     */
    public function getAttribute(string $attribute)
    {
        return $this->attributes[$attribute] ?? null;
    }

    /**
     *
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     *
     */
    public function getHref(): ?string
    {
        return $this->href;
    }

    /**
     *
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     *
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     *
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     *
     */
    public function hasAttribute(string $attribute): bool
    {
        if (empty($this->attributes)) {
            return false;
        }

        foreach ($this->attributes as $key => $value) {

            if ($key == $attribute) {
                return true;
            }
        }

        return false;
    }
}
