<?php

namespace Bayard\NewsletterORMBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;


class NewsletterRepository extends EntityRepository
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
    public function getAdvertisements($page, $numberPerPage)
    {
        $query = $this->createQueryBuilder('a')
            ->leftJoin('a.newsletterTypes', 'nt')
            ->addSelect('nt')
            ->getQuery();

        $query->setFirstResult(($page - 1) * $numberPerPage)
            ->setMaxResults($numberPerPage);

        return new Paginator($query, true);

    }
}
