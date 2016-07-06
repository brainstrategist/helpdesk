<?php

namespace BrainStrategist\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket_Log
 *
 * @ORM\Table(name="ticket__log")
 * @ORM\Entity(repositoryClass="BrainStrategist\ProjectBundle\Repository\Ticket_LogRepository")
 */
class Ticket_Log
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateLog", type="datetime")
     */
    private $dateLog;

    /**
     * @var array
     *
     * @ORM\Column(name="ContentLog", type="json_array")
     */
    private $contentLog;

    /**
     * @ORM\ManyToOne(targetEntity="Ticket", inversedBy="logs")
     * @ORM\JoinColumn(name="ticket_id", referencedColumnName="id")
     */
    private $ticket;

    /**
     * @ORM\ManyToOne(targetEntity="BrainStrategist\KernelBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateLog
     *
     * @param \DateTime $dateLog
     *
     * @return Ticket_Log
     */
    public function setDateLog($dateLog)
    {
        $this->dateLog = $dateLog;

        return $this;
    }

    /**
     * Get dateLog
     *
     * @return \DateTime
     */
    public function getDateLog()
    {
        return $this->dateLog;
    }

    /**
     * Set contentLog
     *
     * @param array $contentLog
     *
     * @return Ticket_Log
     */
    public function setContentLog($contentLog)
    {
        $this->contentLog = $contentLog;

        return $this;
    }

    /**
     * Get contentLog
     *
     * @return array
     */
    public function getContentLog()
    {
        return $this->contentLog;
    }

    /**
     * Constructor
     */
    public function __construct()
    {

        $this->dateLog = new \DateTime();


    }

    /**
     * Set ticket
     *
     * @param \BrainStrategist\ProjectBundle\Entity\Ticket $ticket
     *
     * @return Ticket_Log
     */
    public function setTicket(\BrainStrategist\ProjectBundle\Entity\Ticket $ticket = null)
    {
        $this->ticket = $ticket;

        return $this;
    }

    /**
     * Get ticket
     *
     * @return \BrainStrategist\ProjectBundle\Entity\Ticket
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * Set user
     *
     * @param \BrainStrategist\KernelBundle\Entity\User $user
     *
     * @return Ticket_Log
     */
    public function setUser(\BrainStrategist\KernelBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \BrainStrategist\KernelBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
