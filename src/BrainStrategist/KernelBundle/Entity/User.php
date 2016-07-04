<?php
// src/BrainStrategist/CalendarBundle/Entity/User.php

namespace BrainStrategist\KernelBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Please enter your name.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $first_name;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Please enter your name.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $last_name;


    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="string", nullable=true)
     */
    protected $facebook_id;

    /**
     * @var string
     *
     * @ORM\Column(name="profile_picture", type="string", length=250, nullable=true)
     *
     */
    protected $profilePicture;

    /**
     * @var string
     *
     * @ORM\Column(name="google_id", type="string", nullable=true)
     */
    protected $google_id;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter_id", type="string", nullable=true)
     */
    protected $twitter_id;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Organization", inversedBy="usersOrganization", cascade={"persist", "merge"})
     * @ORM\JoinTable(name="users_organization",
     *   joinColumns={@ORM\JoinColumn(name="User_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="Organization_id", referencedColumnName="id")}
     * )
     */
    protected $organizations;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="BrainStrategist\ProjectBundle\Entity\Project", inversedBy="usersProject", cascade={"persist", "merge"})
     * @ORM\JoinTable(name="users_project",
     *   joinColumns={@ORM\JoinColumn(name="User_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="Project_id", referencedColumnName="id")}
     * )
     */
    protected $projects;


    /**
     * @ORM\ManyToMany(targetEntity="BrainStrategist\ProjectBundle\Entity\Ticket", mappedBy="assigned_users")
     */
    private $user_tickets;

    public function __construct()
    {
        parent::__construct();
        $this->media = new \Doctrine\Common\Collections\ArrayCollection();
        $this->user_tickets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set facebookId
     *
     * @param string $facebookId
     *
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebook_id = $facebookId;

        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Set googleId
     *
     * @param string $googleId
     *
     * @return User
     */
    public function setGoogleId($googleId)
    {
        $this->google_id = $googleId;

        return $this;
    }

    /**
     * Get googleId
     *
     * @return string
     */
    public function getGoogleId()
    {
        return $this->google_id;
    }

    /**
     * Set twitterId
     *
     * @param string $twitterId
     *
     * @return User
     */
    public function setTwitterId($twitterId)
    {
        $this->twitter_id = $twitterId;

        return $this;
    }

    /**
     * Get twitterId
     *
     * @return string
     */
    public function getTwitterId()
    {
        return $this->twitter_id;
    }

    /**
     * Set profilePicture
     *
     * @param string $profilePicture
     *
     * @return User
     */
    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    /**
     * Get profilePicture
     *
     * @return string
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }


    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }


    /**
     * Set organization
     *
     * @param \BrainStrategist\KernelBundle\Entity\Organization $organization
     *
     * @return User
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
     * Add organization
     *
     * @param \BrainStrategist\KernelBundle\Entity\Organization $organization
     *
     * @return User
     */
    public function addOrganization(\BrainStrategist\KernelBundle\Entity\Organization $organization)
    {
        $this->organizations[] = $organization;

        return $this;
    }

    /**
     * Remove organization
     *
     * @param \BrainStrategist\KernelBundle\Entity\Organization $organization
     */
    public function removeOrganization(\BrainStrategist\KernelBundle\Entity\Organization $organization)
    {
        $this->organizations->removeElement($organization);
    }

    /**
     * Get organizations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganizations()
    {
        return $this->organizations;
    }



    /**
     * Add project
     *
     * @param \BrainStrategist\ProjectBundle\Entity\Project $project
     *
     * @return User
     */
    public function addProject(\BrainStrategist\ProjectBundle\Entity\Project $project)
    {
        $this->projects[] = $project;

        return $this;
    }

    /**
     * Remove project
     *
     * @param \BrainStrategist\ProjectBundle\Entity\Project $project
     */
    public function removeProject(\BrainStrategist\ProjectBundle\Entity\Project $project)
    {
        $this->projects->removeElement($project);
    }

    /**
     * Get projects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Add userTicket
     *
     * @param \BrainStrategist\ProjectBundle\Entity\Ticket $userTicket
     *
     * @return User
     */
    public function addUserTicket(\BrainStrategist\ProjectBundle\Entity\Ticket $userTicket)
    {
        $this->user_tickets[] = $userTicket;

        return $this;
    }

    /**
     * Remove userTicket
     *
     * @param \BrainStrategist\ProjectBundle\Entity\Ticket $userTicket
     */
    public function removeUserTicket(\BrainStrategist\ProjectBundle\Entity\Ticket $userTicket)
    {
        $this->user_tickets->removeElement($userTicket);
    }

    /**
     * Get userTickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserTickets()
    {
        return $this->user_tickets;
    }
}
