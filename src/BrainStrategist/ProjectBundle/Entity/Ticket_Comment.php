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
     * Get id
     *
     * @return int
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
}
