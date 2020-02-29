<?php

namespace OmniTools\Core\Persistence\Entity;

/**
 * @Entity
 * @Table(name="log")
 * @HasLifecycleCallbacks
 */
class Log extends \OmniTools\Core\Persistence\AbstractEntity
{
    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    protected $user;

    /**
     * @Column(length=255, nullable=false)
     */
    protected $subjectClass;

    /**
     * @Column(type="integer", nullable=false, options={"unsigned"=true})
     */
    protected $subjectId;

    /**
     * @Column(length=255, nullable=false)
     */
    protected $action;

    /**
     * @Column(type="json")
     */
    protected $context = [];

    /**
     *
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     *
     */
    public function getContextTranslatable(): array
    {
        $list = [
            '%serverpath%' => SERVER_PATH
        ];

        foreach ($this->context as $key => $value) {
            $list['%' . $key . '%'] = $value;
        }

        return $list;
    }
}
