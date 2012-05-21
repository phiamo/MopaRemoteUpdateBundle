<?php

namespace Mopa\Bundle\RemoteUpdateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\SerializerBundle\Annotation as Serializer;

/**
 * Mopa\Bundle\RemoteUpdateBundle\Entity\UpdateJob
 *
 * @ORM\Table(name="mopa_remote_update_jobs")
 * @ORM\Entity(repositoryClass="Mopa\Bundle\RemoteUpdateBundle\Entity\UpdateJobRepository")
 */
class UpdateJob
{
    const STATUS_PENDING = 0;
    const STATUS_RUNNING = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_FAILED = 3;

    /**
     * @var integer $id
     * @Serializer\Type("integer")
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $username
     *
     * @Serializer\Type("string")
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var datetime $createdAt
     *
     * @Serializer\Type("DateTime")
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var datetime $startAt
     *
     * @Serializer\Type("DateTime")
     * @ORM\Column(name="startAt", type="datetime", nullable=true)
     */
    private $startAt = null;

    /**
     * @var datetime $finishedAt
     *
     * @Serializer\Type("DateTime")
     * @ORM\Column(name="finishedAt", type="datetime", nullable=true)
     */
    private $finishedAt = null;

    /**
     * @var string $remote
     *
     * @Serializer\Type("string")
     * @ORM\Column(name="remote", type="string", length=255)
     */
    private $remote;

    /**
     * @var boolean $status
     *
     * @Serializer\Type("integer")
     * @ORM\Column(name="status", type="integer")
     */
    private $status = 0;

    /**
     * @var text $message
     * @Serializer\Type("string")
     * @ORM\Column(name="message", type="text")
     */
    private $message = "";


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     * @return UpdateJob
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set startAt
     *
     * @param datetime $startAt
     * @return UpdateJob
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;
        return $this;
    }

    /**
     * Get startAt
     *
     * @return datetime
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * Set finishedAt
     *
     * @param datetime $finishedAt
     * @return UpdateJob
     */
    public function setFinishedAt($finishedAt)
    {
        $this->finishedAt = $finishedAt;
        return $this;
    }

    /**
     * Get finishedAt
     *
     * @return datetime
     */
    public function getFinishedAt()
    {
        return $this->finishedAt;
    }

    /**
     * Set remote
     *
     * @param string $remote
     * @return UpdateJob
     */
    public function setRemote($remote)
    {
        $this->remote = $remote;
        return $this;
    }

    /**
     * Get remote
     *
     * @return string
     */
    public function getRemote()
    {
        return $this->remote;
    }

    /**
     * Set message
     *
     * @param text $message
     * @return UpdateJob
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Add message
     *
     * @param text $message
     * @return UpdateJob
     */
    public function addMessage($message)
    {
        $this->message .= $message;
        return $this;
    }

    /**
     * Get message
     *
     * @return text
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return UpdateJob
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return UpdateJob
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function getStatusMessage() {
        switch($this->status) {
            case self::STATUS_PENDING:
                return "<comment>pending</comment>";
            case self::STATUS_RUNNING:
                return "<comment>running</comment>";
            case self::STATUS_PENDING:
                return "<info>success</info>";
            case self::STATUS_FAILED:
                return "<error>failed</error>";
        }
    }
}