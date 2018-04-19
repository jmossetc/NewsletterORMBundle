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
     * @ORM\Column(name="image_link", type="string", length =255)
     */
    private $imageLink;

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
     * @ORM\ManyToMany(targetEntity="NewsletterType", inversedBy="advertisements", cascade={"persist"})
     * @ORM\JoinTable(name="advertisement_newsletter_type",
     *      joinColumns={@ORM\JoinColumn(name="advertisement_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     *      )
     */
    private $newsletterTypes;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="AdvertisementDate", mappedBy="advertisement", cascade={"persist"})
     */
    private $dates;

    /**
     * @var
     * @ORM\Column(name="redirect_url", type="text")
     */
    private $redirectURL;

    /**
     * @var
     * @ORM\Column(name="type", type="string", length=255, columnDefinition="ENUM('partner', 'autopromo')")
     */
    private $type;

    /**
     * @var
     * @ORM\Column(name="position", type="smallint")
     */
    private $position;
    /**
     * @var string
     * @ORM\Column(name="target", type="string", columnDefinition="ENUM('all', 'subscribers', 'not_subscribers')")
     */
    private $target;

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
     * @return string
     */
    public function getTarget(){
        return $this->target;
    }

    /**
     * @param string $target
     * @return $this
     */
    public function setTarget($target)
    {
        $this->target = $target;
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
     * Get imageLink
     *
     * @return string
     */
    public function getImageLink()
    {
        return $this->imageLink;
    }

    /**
     * Set imageLink
     *
     * @param $imageLink
     * @return $this
     */
    public function setImageLink($imageLink)
    {
        $this->imageLink = $imageLink;

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
        if($this->getNewsletterTypes()->contains($newsletterType)){
            $this->newsletterTypes->removeElement($newsletterType);

        }
        else{
            return;
        }
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
        $date->setAdvertisement($this);
        $this->dates[] = $date;

        return $this;
    }

    /**
     * remove date
     * @param \Bayard\NewsletterORMBundle\Entity\AdvertisementDate $date
     */
    public function removeDate(AdvertisementDate $date){
        $this->dates->removeElement($date);
    }


    /**
     * @return mixed
     */
    public function getRedirectURL(){
        return $this->redirectURL;
    }
    /**
     * @param $url
     * @return $this
     */
    public function setRedirectURL($url){
        $this->redirectURL = $url;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType(){
        return $this->type;
    }
    /**
     * @param $type
     * @return $this
     */
    public function setType($type){
        $this->type = $type;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getPosition(){
        return $this->position;
    }
    /**
     * @param $pos
     * @return $this
     */
    public function setPosition($pos){
        $this->position = $pos;

        return $this;
    }


}