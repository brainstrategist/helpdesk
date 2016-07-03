<?php

namespace BrainStrategist\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="BrainStrategist\ProjectBundle\Repository\ProjectRepository")
 */
class Project
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @Gedmo\Slug(fields={"name", "id"})
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var bool
     *
     * @ORM\Column(name="Is_active", type="boolean")
     */
    private $isActive= true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_creation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="BrainStrategist\KernelBundle\Entity\User", mappedBy="projects", cascade={"persist", "merge"})
     */
    protected $usersProject;

    /**
     * @var ArrayCollection
     *
     * @ORM\oneToMany(targetEntity="Ticket", mappedBy="projet", cascade={"persist", "merge"})
     */
    private $projectTickets;

    /**
     * @ORM\ManyToOne(targetEntity="BrainStrategist\KernelBundle\Entity\User")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     */
    private $creator;

    /**
     * @ORM\ManyToOne(targetEntity="BrainStrategist\KernelBundle\Entity\Organization", inversedBy="projectsOrganization")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id")
     */
    private $organization;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\File(mimeTypes={ "image/jpeg" })
     */
    private $picture;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usersProject = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dateCreation = new \DateTime();
    }
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
     * Set name
     *
     * @param string $name
     *
     * @return Project
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Project
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Project
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Project
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
     * Add usersProject
     *
     * @param \BrainStrategist\KernelBundle\Entity\User $usersProject
     *
     * @return Project
     */
    public function addUsersProject(\BrainStrategist\KernelBundle\Entity\User $usersProject)
    {
        $this->usersProject[] = $usersProject;

        return $this;
    }

    /**
     * Remove usersProject
     *
     * @param \BrainStrategist\KernelBundle\Entity\User $usersProject
     */
    public function removeUsersProject(\BrainStrategist\KernelBundle\Entity\User $usersProject)
    {
        $this->usersProject->removeElement($usersProject);
    }

    /**
     * Get usersProject
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsersProject()
    {
        return $this->usersProject;
    }

    /**
     * Set creator
     *
     * @param \BrainStrategist\KernelBundle\Entity\User $creator
     *
     * @return Project
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
     * Set organization
     *
     * @param \BrainStrategist\KernelBundle\Entity\Organization $organization
     *
     * @return Project
     */
    public function setOrganization(\BrainStrategist\KernelBundle\Entity\Organization $organization = null)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return \BrainStrategist\KernelBundle\Entity\Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Set picture
     *
     * @param string $picture
     *
     * @return Project
     */
    public function setPicture($picture)
    {
        if($picture !== null) {
            $this->picture = $picture;

            return $this;
        }
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
     * Add projectTicket
     *
     * @param \BrainStrategist\ProjectBundle\Entity\Ticket $projectTicket
     *
     * @return Project
     */
    public function addProjectTicket(\BrainStrategist\ProjectBundle\Entity\Ticket $projectTicket)
    {
        $this->projectTickets[] = $projectTicket;

        return $this;
    }

    /**
     * Remove projectTicket
     *
     * @param \BrainStrategist\ProjectBundle\Entity\Ticket $projectTicket
     */
    public function removeProjectTicket(\BrainStrategist\ProjectBundle\Entity\Ticket $projectTicket)
    {
        $this->projectTickets->removeElement($projectTicket);
    }

    /**
     * Get projectTickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjectTickets()
    {
        return $this->projectTickets;
    }
}
