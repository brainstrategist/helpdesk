<?php

namespace BrainStrategist\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

use BrainStrategist\KernelBundle\Entity\Picture;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="BrainStrategist\ProjectBundle\Repository\TicketRepository")
 */
class Ticket
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
     * @var int
     *
     * @ORM\Column(name="order_ticket", type="integer")
     */
    private $order;

    /**
     * @var string
     *
     * @ORM\Column(name="Identifier", type="string", length=255)
     */
    private $identifier;

    /**
     * @var string
     *
     * @ORM\Column(name="Summary", type="string", length=255)
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="text")
     */
    private $description;

    /**
     * @var array
     *
     * @ORM\Column(name="Browser", type="array")
     */
    private $browser;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_creation", type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\ManyToOne(targetEntity="Ticket_Priority")
     * @ORM\JoinColumn(name="priority_id", referencedColumnName="id")
     */
    private $priority;

    /**
     *
     * @ORM\OneToMany(targetEntity="BrainStrategist\KernelBundle\Entity\Picture", mappedBy="ticket", cascade={"persist"})
     */
    private $pictures;

    /**
     * @ORM\ManyToOne(targetEntity="BrainStrategist\KernelBundle\Entity\User")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     */
    private $creator;

    /**
     * @ORM\ManyToOne(targetEntity="Severity")
     * @ORM\JoinColumn(name="severity_id", referencedColumnName="id")
     */
    private $severity;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="projectTickets")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $projet;

    /**
     * @ORM\ManyToOne(targetEntity="Ticket_Status")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="Ticket_Category", inversedBy="ticketsCategory")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="Ticket_Comment", mappedBy="ticket")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="Ticket_Log", mappedBy="ticket",cascade={"persist"})
     */
    private $logs;


    /**
     * @ORM\ManyToMany(targetEntity="BrainStrategist\KernelBundle\Entity\User", inversedBy="user_tickets",cascade={"persist"}, indexBy="ticket_id")
     * @ORM\JoinTable(name="users_tickets")
     */
    private $assigned_users;

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
     * Set summary
     *
     * @param string $summary
     *
     * @return Ticket
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Ticket
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set browser
     *
     * @param array $browser
     *
     * @return Ticket
     */
    public function setBrowser($browser)
    {
        $this->browser = $browser;

        return $this;
    }

    /**
     * Get browser
     *
     * @return array
     */
    public function getBrowser()
    {
        return $this->browser;
    }


    /**
     * Set priority
     *
     * @param integer $priority
     *
     * @return Ticket
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set picture
     *
     * @param string $picture
     *
     * @return Ticket
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set creator
     *
     * @param \BrainStrategist\KernelBundle\Entity\User $creator
     *
     * @return Ticket
     */
    public function setCreator(\BrainStrategist\KernelBundle\Entity\User $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \BrainStrategist\KernelBundle\Entity\User
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set projet
     *
     * @param \BrainStrategist\ProjectBundle\Entity\Project $projet
     *
     * @return Ticket
     */
    public function setProjet(\BrainStrategist\ProjectBundle\Entity\Project $projet = null)
    {
        $this->projet = $projet;

        return $this;
    }

    /**
     * Get projet
     *
     * @return \BrainStrategist\ProjectBundle\Entity\Project
     */
    public function getProjet()
    {
        return $this->projet;
    }

    /**
     * Set category
     *
     * @param \BrainStrategist\ProjectBundle\Entity\Ticket_Category $category
     *
     * @return Ticket
     */
    public function setCategory(\BrainStrategist\ProjectBundle\Entity\Ticket_Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \BrainStrategist\ProjectBundle\Entity\Ticket_Category
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assigned_users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->severity = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dateCreation = new \DateTime();
        $this->identifier = substr(strtoupper(md5(uniqid(rand(), true))), 0, 6);
        $this->pictures = new ArrayCollection();
    }

    /**
     * Add comment
     *
     * @param \BrainStrategist\ProjectBundle\Entity\Ticket_Comment $comment
     *
     * @return Ticket
     */
    public function addComment(\BrainStrategist\ProjectBundle\Entity\Ticket_Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \BrainStrategist\ProjectBundle\Entity\Ticket_Comment $comment
     */
    public function removeComment(\BrainStrategist\ProjectBundle\Entity\Ticket_Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set severity
     *
     * @param \BrainStrategist\ProjectBundle\Entity\Severity $severity
     *
     * @return Ticket
     */
    public function setSeverity(\BrainStrategist\ProjectBundle\Entity\Severity $severity = null)
    {
        $this->severity = $severity;

        return $this;
    }

    /**
     * Get severity
     *
     * @return \BrainStrategist\ProjectBundle\Entity\Severity
     */
    public function getSeverity()
    {
        return $this->severity;
    }

    /**
     * Add assignedUser
     *
     * @param \BrainStrategist\KernelBundle\Entity\User $assignedUser
     *
     * @return Ticket
     */
    public function addAssignedUser(\BrainStrategist\KernelBundle\Entity\User $assignedUser)
    {
        $this->assigned_users[] = $assignedUser;

        return $this;
    }

    /**
     * Remove assignedUser
     *
     * @param \BrainStrategist\KernelBundle\Entity\User $assignedUser
     */
    public function removeAssignedUser(\BrainStrategist\KernelBundle\Entity\User $assignedUser)
    {
        $this->assigned_users->removeElement($assignedUser);
    }

    /**
     * Get assignedUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAssignedUsers()
    {
        return $this->assigned_users;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Ticket
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Add log
     *
     * @param \BrainStrategist\ProjectBundle\Entity\Ticket_Log $log
     *
     * @return Ticket
     */
    public function addLog(\BrainStrategist\ProjectBundle\Entity\Ticket_Log $log)
    {
        $this->logs[] = $log;

        return $this;
    }

    /**
     * Remove log
     *
     * @param \BrainStrategist\ProjectBundle\Entity\Ticket_Log $log
     */
    public function removeLog(\BrainStrategist\ProjectBundle\Entity\Ticket_Log $log)
    {
        $this->logs->removeElement($log);
    }

    /**
     * Get logs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * Set status
     *
     * @param \BrainStrategist\ProjectBundle\Entity\Ticket_Status $status
     *
     * @return Ticket
     */
    public function setStatus(\BrainStrategist\ProjectBundle\Entity\Ticket_Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \BrainStrategist\ProjectBundle\Entity\Ticket_Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     *
     * @return Ticket
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Add picture
     *
     * @param \BrainStrategist\KernelBundle\Entity\Picture $picture
     *
     * @return Ticket
     */
    public function addPicture(\BrainStrategist\KernelBundle\Entity\Picture $picture)
    {
        $this->pictures[] = $picture;
        $picture->setTicket($this);

        return $this;
    }

    /**
     * Remove picture
     *
     * @param \BrainStrategist\KernelBundle\Entity\Picture $picture
     */
    public function removePicture(\BrainStrategist\KernelBundle\Entity\Picture $picture)
    {
        $this->pictures->removeElement($picture);
    }

    /**
     * Get pictures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * Set order
     *
     * @param integer $order
     *
     * @return Ticket
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }
}
