<?php

namespace Bayard\NewsletterORMBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Query\Expr\Join;

/**
 * NewsletterRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NewsletterRepository extends EntityRepository
{

    /**
     * @var string
     */
    protected $alias = 'newsletter';

    /**
     * @param $page
     * @param $numberPerPage
     * @return Paginator
     */
    public function getNewsletters($page, $numberPerPage, $sortArray = [])
    {
        $query = $this->createQueryBuilder('newsletter')
            ->leftJoin('newsletter.newsletterType', 't')
            ->addSelect('newsletter');

        foreach ($sortArray as $sort){
            $query->addOrderBy($sort['column'], $sort['order']);
        }

        $query->getQuery();

        $query->setFirstResult(($page - 1) * $numberPerPage)
            ->setMaxResults($numberPerPage);

        return new Paginator($query, true);
    }

    public function getApplicableAds($newsletterId){
        $newsletter = $this->find($newsletterId);

        $qb = $this->getEntityManager()->createQueryBuilder();

        $query = $qb->select(['imageLink', 'redirectURL', 'position'])
            ->from('BayardNewsletterORMBundle:Advertisement', 'a')
            ->leftJoin('a.newsletterTypes', 'type', Join::WITH, $qb->expr()->andX(
                $qb->expr()->eq('type.name', ':type')
            ))
            ->leftJoin('a.dates', 'dates', Join::WITH, $qb->expr()->andX(
                $qb->expr()->lte('dates.beginningDate', ':date'),
                $qb->expr()->gte('dates.endDate', ':date')
            ))
        ->setParameters([
            'type' => $newsletter->getNewsletterType(),
            'date' => $newsletter->getDispatchDate()
        ]);

        return $query->getQuery()->getResult();
    }
}
