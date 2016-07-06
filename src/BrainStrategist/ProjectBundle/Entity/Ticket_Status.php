<?php

namespace BrainStrategist\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket_Status
 *
 * @ORM\Table(name="ticket__status")
 * @ORM\Entity(repositoryClass="BrainStrategist\ProjectBundle\Repository\Ticket_StatusRepository")
 */
class Ticket_Status
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
     * @ORM\Column(name="Name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="text", length=255)
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="Is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="severity_level")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;


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
     * Set name
     *
     * @param string $name
     *
     * @return Ticket_Status
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Ticket_Status
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
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Ticket_Status
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set project
     *
     * @param \BrainStrategist\ProjectBundle\Entity\Project $project
     *
     * @return Ticket_Status
     */
    public function setProject(\BrainStrategist\ProjectBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \BrainStrategist\ProjectBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }
}
