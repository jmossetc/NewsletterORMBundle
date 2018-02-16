<?php

namespace Bayard\NewsletterORMBundle\Entity;

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
     * @ORM\Column(name="enabled", type="boolean", options={"default" = true})
     */
    private $enabled;


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
    public function setNewsletterType(\Bayard\NewsletterORMBundle\Entity\NewsletterType $newsletterType = null)
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
}
