<?php
/**
 * Created by PhpStorm.
 * User: jmossetc
 * Date: 20/02/18
 * Time: 17:27
 */

namespace Bayard\NewsletterORMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Bayard\NewsletterORMBundle\Entity\NewsletterType;
use Bayard\NewsletterORMBundle\Entity\AdvertisementDate;


/**
 * Advertisement
 *
 * @ORM\Table(name="advertisement")
 * @ORM\Entity(repositoryClass="Bayard\NewsletterORMBundle\Repository\AdvertisementRepository")
 */
class Advertisement
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
     *
     * @ORM\Column(name="image_location", type="string", length =255)
     */
    private $imageLocation;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="string", length =255)
     */
    private $fileName;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="NewsletterType", cascade={"persist"})
     * @ORM\JoinTable(name="advertisement_newsletter_type",
     *      joinColumns={@ORM\JoinColumn(name="advertisement_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     *      )
     */
    private $newsletterTypes;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="AdvertismentDate")
     */
    private $dates;

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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
    /**
     * Get imageLocation
     *
     * @return string
     */
    public function getImageLocation()
    {
        return $this->imageLocation;
    }

    /**
     * Set imageLocation
     *
     * @param $imageLocation
     * @return $this
     */
    public function setImageLocation($imageLocation)
    {
        $this->imageLocation = $imageLocation;

        return $this;
    }
    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set fileName
     *
     * @param $fileName
     * @return $this
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }
    /**
     * Get enabled
     *
     * @return string
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set enabled
     *
     * @param $enabled
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * add newsletterType
     *
     * @param NewsletterType $newsletterType
     *
     * @return Advertisement
     */
    public function addNewsletterType(NewsletterType $newsletterType = null)
    {
        $this->newsletterTypes[] = $newsletterType;

        return $this;
    }

    /**
     * Remove newsletterType
     * @param \Bayard\NewsletterORMBundle\Entity\NewsletterType $newsletterType
     */
    public function removeNewsletterType(NewsletterType $newsletterType){
        $this->newsletterTypes->removeElement($newsletterType);
    }
    /**
     * Get newsletterType
     *
     * @return \Bayard\NewsletterORMBundle\Entity\NewsletterType
     */
    public function getNewsletterTypes()
    {
        return $this->newsletterTypes;
    }

    /**
     * get dates
     *
     * @return mixed
     */
    public function getDates(){
        return $this->dates;
    }

    /**
     * add date
     * @param \Bayard\NewsletterORMBundle\Entity\AdvertisementDate $date
     */
    public function addDate(AdvertisementDate $date){
        $this->dates[] = $date;
    }

    /**
     * remove date
     * @param \Bayard\NewsletterORMBundle\Entity\AdvertisementDate $date
     */
    public function removeDate(AdvertisementDate $date){
        $this->dates->removeElement($date);
    }

}