<?php

namespace BrainStrategist\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket_Comment
 *
 * @ORM\Table(name="ticket__comment")
 * @ORM\Entity(repositoryClass="BrainStrategist\ProjectBundle\Repository\Ticket_CommentRepository")
 */
class Ticket_Comment
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
     * @var string
     *
     * @ORM\Column(name="ContentComment", type="text")
     */
    private $contentComment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateComment", type="datetime")
     */
    private $dateComment;

    /**
     * @ORM\ManyToOne(targetEntity="Ticket", inversedBy="comments")
     * @ORM\JoinColumn(name="ticket_id", referencedColumnName="id")
     */
    private $ticket;

    /**
     * @ORM\ManyToOne(targetEntity="Ticket_Status")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $ticket_status;
    /**
     * @ORM\ManyToOne(targetEntity="BrainStrategist\KernelBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user_comment;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dateComment = new \DateTime();

    }


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
     * Set contentComment
     *
     * @param string $contentComment
     *
     * @return Ticket_Comment
     */
    public function setContentComment($contentComment)
    {
        $this->contentComment = $contentComment;

        return $this;
    }

    /**
     * Get contentComment
     *
     * @return string
     */
    public function getContentComment()
    {
        return $this->contentComment;
    }

    /**
     * Set dateComment
     *
     * @param \DateTime $dateComment
     *
     * @return Ticket_Comment
     */
    public function setDateComment($dateComment)
    {
        $this->dateComment = $dateComment;

        return $this;
    }

    /**
     * Get dateComment
     *
     * @return \DateTime
     */
    public function getDateComment()
    {
        return $this->dateComment;
    }

    /**
     * Set ticket
     *
     * @param \BrainStrategist\ProjectBundle\Entity\Ticket $ticket
     *
     * @return Ticket_Comment
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
     * Set userComment
     *
     * @param \BrainStrategist\KernelBundle\Entity\User $userComment
     *
     * @return Ticket_Comment
     */
    public function setUserComment(\BrainStrategist\KernelBundle\Entity\User $userComment = null)
    {
        $this->user_comment = $userComment;

        return $this;
    }

    /**
     * Get userComment
     *
     * @return \BrainStrategist\KernelBundle\Entity\User
     */
    public function getUserComment()
    {
        return $this->user_comment;
    }

    /**
     * Set ticketStatus
     *
     * @param \BrainStrategist\ProjectBundle\Entity\Ticket_Status $ticketStatus
     *
     * @return Ticket_Comment
     */
    public function setTicketStatus(\BrainStrategist\ProjectBundle\Entity\Ticket_Status $ticketStatus = null)
    {
        $this->ticket_status = $ticketStatus;

        return $this;
    }

    /**
     * Get ticketStatus
     *
     * @return \BrainStrategist\ProjectBundle\Entity\Ticket_Status
     */
    public function getTicketStatus()
    {
        return $this->ticket_status;
    }
}
