<?php

namespace Bayard\NewsletterORMBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;


class AdvertisementRepository extends EntityRepository
{

    /**
     * @var string
     */
    protected $alias = 'advertisement';

    /**
     * @param $page
     * @param $numberPerPage
     * @return Paginator
     */
    public function getAdvertisements($page, $numberPerPage, $sortArray = [])
    {
        $query = $this->createQueryBuilder('advertisement')
            ->leftJoin('advertisement.newsletterTypes', 'nt')
            ->leftJoin('advertisement.dates', 'd')
            ->addSelect('nt')
            ->addSelect('d');

        foreach ($sortArray as $sort){
            $query->addOrderBy($sort['column'], $sort['order']);
        }

        $query->getQuery();

        $query->setFirstResult(($page - 1) * $numberPerPage)
            ->setMaxResults($numberPerPage);

        return new Paginator($query, true);

    }
}
