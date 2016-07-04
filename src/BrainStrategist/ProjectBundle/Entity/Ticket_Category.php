<?php

namespace BrainStrategist\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket_Category
 *
 * @ORM\Table(name="ticket__category")
 * @ORM\Entity(repositoryClass="BrainStrategist\ProjectBundle\Repository\Ticket_CategoryRepository")
 */
class Ticket_Category
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
     * @var ArrayCollection
     *
     * @ORM\oneToMany(targetEntity="Ticket", mappedBy="category", cascade={"persist", "merge"})
     */
    private $ticketsCategory;

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
     * @return Ticket_Category
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
     * Constructor
     */
    public function __construct()
    {
        $this->ticketsCategory = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ticketsCategory
     *
     * @param \BrainStrategist\ProjectBundle\Entity\Ticket $ticketsCategory
     *
     * @return Ticket_Category
     */
    public function addTicketsCategory(\BrainStrategist\ProjectBundle\Entity\Ticket $ticketsCategory)
    {
        $this->ticketsCategory[] = $ticketsCategory;

        return $this;
    }

    /**
     * Remove ticketsCategory
     *
     * @param \BrainStrategist\ProjectBundle\Entity\Ticket $ticketsCategory
     */
    public function removeTicketsCategory(\BrainStrategist\ProjectBundle\Entity\Ticket $ticketsCategory)
    {
        $this->ticketsCategory->removeElement($ticketsCategory);
    }

    /**
     * Get ticketsCategory
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTicketsCategory()
    {
        return $this->ticketsCategory;
    }
}
