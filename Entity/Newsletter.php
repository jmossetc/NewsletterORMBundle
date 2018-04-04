<?php

namespace Bayard\NewsletterORMBundle\Entity;

use Bayard\NewsletterORMBundle\Entity\NewsletterType;
use Doctrine\ORM\Mapping as ORM;

/**
 * Newsletter
 *
 * @ORM\Table(name="newsletter")
 * @ORM\Entity(repositoryClass="Bayard\NewsletterORMBundle\Repository\NewsletterRepository")
 */
class Newsletter
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
     * @ORM\Column(name="nb_positions", type="integer")
     */
    private $nbPositions;

    /**
     * @var int
     *
     * @ORM\Column(name="open4_id", type="integer")
     */
    private $open4Id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="xml_location", type="string", length=255)
     */
    private $xmlLocation;

    /**
     * @var string
     *
     * @ORM\Column(name="html_location", type="string", length=255)
     */
    private $htmlLocation;

    /**
     * @var string
     *
     * @ORM\Column(name="text_location", type="string", length=255)
     */
    private $textLocation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dispatchDate", type="datetime")
     */
    private $dispatchDate;

    /**
     * @ORM\ManyToOne(targetEntity="NewsletterType")
     * @ORM\JoinColumn(name="newsletter_type_id", referencedColumnName="id")
     */
    private $newsletterType;

    /**
     * @var boolean
     * @ORM\Column(name="status", type="string", columnDefinition="ENUM('sent', 'abandonned', 'building', 'received', 'programmed')")
     */
    private $status;


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
     * @return int
     */
    public function getOpen4Id()
    {
        return $this->open4Id;
    }

    /**
     * @param int $open4Id
     * @return Newsletter
     */
    public function setOpen4Id($open4Id)
    {
        $this->open4Id = $open4Id;
        return $this;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Newsletter
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set xmlLocation
     *
     * @param string $xmlLocation
     *
     * @return Newsletter
     */
    public function setXmlLocation($xmlLocation)
    {
        $this->xmlLocation = $xmlLocation;
        return $this;
    }

    /**
     * Get xmlLocation
     *
     * @return string
     */
    public function getXmlLocation()
    {
        return $this->xmlLocation;
    }

    /**
     * Set htmlLocation
     *
     * @param string $htmlLocation
     *
     * @return Newsletter
     */
    public function setHtmlLocation($htmlLocation)
    {
        $this->htmlLocation = $htmlLocation;
        return $this;
    }

    /**
     * Get textLocation
     *
     * @return string
     */
    public function getTextLocation()
    {
        return $this->textLocation;
    }

    /**
     * Set textLocation
     *
     * @param string $textLocation
     *
     * @return Newsletter
     */
    public function setTextLocation($textLocation)
    {
        $this->textLocation = $textLocation;
        return $this;
    }

    /**
     * Get htmlLocation
     *
     * @return string
     */
    public function getHtmlLocation()
    {
        return $this->htmlLocation;
    }


    /**
     * Set dispatchDate
     *
     * @param \DateTime $dispatchDate
     *
     * @return Newsletter
     */
    public function setDispatchDate($dispatchDate)
    {
        $this->dispatchDate = $dispatchDate;
        return $this;
    }

    /**
     * Get dispatchDate
     *
     * @return \DateTime
     */
    public function getDispatchDate()
    {
        return $this->dispatchDate;
    }

    /**
     * Set newsletterType
     *
     * @param \Bayard\NewsletterORMBundle\Entity\NewsletterType $newsletterType
     *
     * @return Newsletter
     */
    public function setNewsletterType(NewsletterType $newsletterType = null)
    {
        $this->newsletterType = $newsletterType;
        return $this;
    }

    /**
     * Get newsletterType
     *
     * @return \Bayard\NewsletterORMBundle\Entity\NewsletterType
     */
    public function getNewsletterType()
    {
        return $this->newsletterType;
    }

    /**
     * Set status
     *
     * @param string $xmlLocation
     *
     * @return Newsletter
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $nbPositions
     * @return Newsletter
     */
    public function setNbPositions($nbPositions)
    {
        $this->nbPositions = $nbPositions;
        return $this;
    }

    /**
     * @return int
     */
    public function getNbPositions()
    {
        return $this->nbPositions;
    }
}
