<?php

namespace BrainStrategist\KernelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Organization
 *
 * @ORM\Table(name="organization")
 * @ORM\Entity(repositoryClass="BrainStrategist\KernelBundle\Repository\OrganizationRepository")
 */
class Organization
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
     * @ORM\Column(name="Name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name", "id"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @var bool
     *
     * @ORM\Column(name="Is_active", type="boolean")
     */
    private $isActive = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_creation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="organizations", cascade={"persist", "merge"})
     */
    protected $usersOrganization;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BrainStrategist\ProjectBundle\Entity\Project", mappedBy="organization", cascade={"persist", "merge"})
     */
    protected $projectsOrganization;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     */
    private $creator;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\File(mimeTypes={ "image/jpeg" })
     */
    private $picture;

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
     * @return Organization
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
     * @return Organization
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
     * @return Organization
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
     * @return Organization
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
     * Constructor
     */
    public function __construct()
    {
        $this->userOrganization = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dateCreation = new \DateTime();
    }


    /**
     * Set creator
     *
     * @param \BrainStrategist\KernelBundle\Entity\User $creator
     *
     * @return Organization
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
     * Set picture
     *
     * @param string $picture
     *
     * @return Organization
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
     * Get projectsOrganization
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjectsOrganization()
    {
        return $this->projectsOrganization;
    }

    /**
     * Add projectsOrganization
     *
     * @param \BrainStrategist\ProjectBundle\Entity\Project $projectsOrganization
     *
     * @return Organization
     */
    public function addProjectsOrganization(\BrainStrategist\ProjectBundle\Entity\Project $projectsOrganization)
    {
        $this->projectsOrganization[] = $projectsOrganization;

        return $this;
    }

    /**
     * Remove projectsOrganization
     *
     * @param \BrainStrategist\ProjectBundle\Entity\Project $projectsOrganization
     */
    public function removeProjectsOrganization(\BrainStrategist\ProjectBundle\Entity\Project $projectsOrganization)
    {
        $this->projectsOrganization->removeElement($projectsOrganization);
    }

    /**
     * Add usersOrganization
     *
     * @param \BrainStrategist\KernelBundle\Entity\User $usersOrganization
     *
     * @return Organization
     */
    public function addUsersOrganization(\BrainStrategist\KernelBundle\Entity\User $usersOrganization)
    {
        $this->usersOrganization[] = $usersOrganization;

        return $this;
    }

    /**
     * Remove usersOrganization
     *
     * @param \BrainStrategist\KernelBundle\Entity\User $usersOrganization
     */
    public function removeUsersOrganization(\BrainStrategist\KernelBundle\Entity\User $usersOrganization)
    {
        $this->usersOrganization->removeElement($usersOrganization);
    }

    /**
     * Get usersOrganization
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsersOrganization()
    {
        return $this->usersOrganization;
    }
}
