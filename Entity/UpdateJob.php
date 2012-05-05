<?php

namespace Mopa\Bundle\RemoteUpdateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mopa\Bundle\RemoteUpdateBundle\Entity\UpdateJob
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mopa\Bundle\RemoteUpdateBundle\Entity\UpdateJobRepository")
 */
class UpdateJob
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $user
     *
     * @ORM\Column(name="user", type="string", length=255)
     */
    private $user;

    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var datetime $startAt
     *
     * @ORM\Column(name="startAt", type="datetime")
     */
    private $startAt;

    /**
     * @var datetime $finishedAt
     *
     * @ORM\Column(name="finishedAt", type="datetime")
     */
    private $finishedAt;

    /**
     * @var string $updater
     *
     * @ORM\Column(name="updater", type="string", length=255)
     */
    private $updater;


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
     * Set user
     *
     * @param string $user
     * @return UpdateJob
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
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
     * Set updater
     *
     * @param string $updater
     * @return UpdateJob
     */
    public function setUpdater($updater)
    {
        $this->updater = $updater;
        return $this;
    }

    /**
     * Get updater
     *
     * @return string
     */
    public function getUpdater()
    {
        return $this->updater;
    }
}