<?php

namespace Bayard\NewsletterORMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NewsletterType
 *
 * @ORM\Table(name="newsletter_type")
 * @ORM\Entity(repositoryClass="Bayard\NewsletterORMBundle\Repository\NewsletterTypeRepository")
 */
class NewsletterType
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
     * @var
     *
     * @ORM\ManyToMany(targetEntity="Advertisement", mappedBy="newsletterTypes")
     */
    private $advertisements;


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
     * @return NewsletterType
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

    public function removeAdvertisement(Advertisement $ad){
        if($this->advertisements->contains($ad)){
            $this->advertisements->removeElement($ad);
            $ad->removeNewsletterType($this);
        }
        else{
            return;
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

}

