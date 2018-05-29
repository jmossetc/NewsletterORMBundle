<?php

namespace Bayard\NewsletterORMBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Query\Expr\Join;


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
    public function getAdvertisements($page, $numberPerPage, $sortArray = [], $filter)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $condition = null;

        $query = $qb->select('a')
            ->from('BayardNewsletterORMBundle:Advertisement', 'a');
        $now = new \DateTime();
        $now->setTime(0, 0, 0);
        switch ($filter) {
            case 'past':
                $query->innerJoin('a.dates', 'd', JOIN::ON)
                    ->leftJoin('a.newsletterTypes', 'nt')
                    ->where('d.endDate <= :now')
                    ->setParameter('now', $now)
                    ->addSelect('nt');;
                break;
            case 'current':
                $query->innerJoin('a.dates', 'd', JOIN::ON)
                    ->leftJoin('a.newsletterTypes', 'nt')
                    ->where('d.endDate >= :now')
                    ->setParameter('now', $now)
                    ->addSelect('nt');;

                break;
            case 'urbi':
            case 'essentiel':
            case 'journal':
                $query->leftJoin('a.dates', 'd')
                    ->leftJoin('a.newsletterTypes', 'nt')
                    ->leftJoin('a.newsletterTypes', 'nt2')->addSelect('nt2')
                    ->where($qb->expr()->eq('nt.name', ':type')                    )
                    ->setParameter('type', $filter);
                break;
            case 'all':
            default:
                $query->leftJoin('a.newsletterTypes', 'nt')
                    ->leftJoin('a.dates', 'd')
                    ->addSelect('nt');
        }


        $query->addSelect('d');

        foreach ($sortArray as $sort) {
            $query->addOrderBy($sort['column'], $sort['order']);
        }

        $query->getQuery();

        $query->setFirstResult(($page - 1) * $numberPerPage)
            ->setMaxResults($numberPerPage);

        return new Paginator($query, true);

    }
}
