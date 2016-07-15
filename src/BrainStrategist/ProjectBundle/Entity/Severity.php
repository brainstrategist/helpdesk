<?php

namespace BrainStrategist\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Severity
 *
 * @ORM\Table(name="severity")
 * @ORM\Entity(repositoryClass="BrainStrategist\ProjectBundle\Repository\SeverityRepository")
 */
class Severity
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->responseTime = new \DateTime();
        $this->responseTimeDays = 1;
        $this->resolutionTime = new \DateTime();
        $this->resolutionTimeDays = 1;
    }
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
     * @var int
     *
     * @ORM\Column(name="Level", type="integer")
     */
    private $level;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="text", length=255)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Response_Time", type="time")
     */
    private $responseTime;

    /**
     * @var int
     *
     * @ORM\Column(name="Response_Time_Days", type="integer")
     */
    private $responseTimeDays;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Resolution_Time", type="time")
     */
    private $resolutionTime;


    /**
     * @var int
     *
     * @ORM\Column(name="Resolution_Time_Days", type="integer")
     */
    private $resolutionTimeDays;

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
     * @return Severity
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
     * Set level
     *
     * @param integer $level
     *
     * @return Severity
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Severity
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
     * Set responseTime
     *
     * @param \DateTime $responseTime
     *
     * @return Severity
     */
    public function setResponseTime($responseTime)
    {
        $this->responseTime = $responseTime;

        return $this;
    }

    /**
     * Get responseTime
     *
     * @return \DateTime
     */
    public function getResponseTime()
    {
        return $this->responseTime;
    }

    /**
     * Set resolutionTime
     *
     * @param \DateTime $resolutionTime
     *
     * @return Severity
     */
    public function setResolutionTime($resolutionTime)
    {
        $this->resolutionTime = $resolutionTime;

        return $this;
    }

    /**
     * Get resolutionTime
     *
     * @return \DateTime
     */
    public function getResolutionTime()
    {
        return $this->resolutionTime;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Severity
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
     * Set project
     *
     * @param \BrainStrategist\ProjectBundle\Entity\Project $project
     *
     * @return Severity
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

    /**
     * Set responseTimeDays
     *
     * @param integer $responseTimeDays
     *
     * @return Severity
     */
    public function setResponseTimeDays($responseTimeDays)
    {
        $this->responseTimeDays = $responseTimeDays;

        return $this;
    }

    /**
     * Get responseTimeDays
     *
     * @return integer
     */
    public function getResponseTimeDays()
    {
        return $this->responseTimeDays;
    }

    /**
     * Set resolutionTimeDays
     *
     * @param integer $resolutionTimeDays
     *
     * @return Severity
     */
    public function setResolutionTimeDays($resolutionTimeDays)
    {
        $this->resolutionTimeDays = $resolutionTimeDays;

        return $this;
    }

    /**
     * Get resolutionTimeDays
     *
     * @return integer
     */
    public function getResolutionTimeDays()
    {
        return $this->resolutionTimeDays;
    }
}
