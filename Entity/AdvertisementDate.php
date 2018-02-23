<?php
/**
 * Created by PhpStorm.
 * User: jmossetc
 * Date: 23/02/18
 * Time: 15:21
 */

namespace Bayard\NewsletterORMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NewsletterType
 *
 * @ORM\Table(name="advertisement_date")
 * @ORM\Entity(repositoryClass="Bayard\NewsletterORMBundle\Repository\AdvertisementRepository")
 */
class AdvertisementDate
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
     * @var \DateTime
     *
     * @ORM\Column(name="beginningDate", type="datetime")
     */
    private $beginningDate;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetime")
     */
    private $endDate;

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
     * get beginningDate
     *
     * @return \DateTime
     */
    public function getBeginningDate(){
        return $this->beginningDate;
    }

    /**
     * set beginningDate
     *
     * @param $date
     * @return $this
     */
    public function setBeginningDate($date){
        $this->beginningDate = $date;
        return $this;
    }

    /**
     * get endDate
     *
     * @return \DateTime
     */
    public function getEndDate(){
        return $this->endDate;
    }

    /**
     * set endDate
     *
     * @param $date
     * @return $this
     */
    public function setEndDate($date){
        $this->endDate = $date;

        return $this;
    }
}