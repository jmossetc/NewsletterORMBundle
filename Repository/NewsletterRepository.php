<?php

namespace Bayard\NewsletterORMBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Query\Expr\Join;

/**
 * Class NewsletterRepository
 * @package Bayard\NewsletterORMBundle\Repository
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

        foreach ($sortArray as $sort) {
            $query->addOrderBy($sort['column'], $sort['order']);
        }

        $query->getQuery();

        $query->setFirstResult(($page - 1) * $numberPerPage)
            ->setMaxResults($numberPerPage);

        return new Paginator($query, true);
    }

    /**
     * @param $open4Id
     * @param $newsletterType
     * @return mixed
     */
    public function getNewslettersFromOpen4Id($open4Id, $newsletterType)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $query = $qb->select('n')
            ->from('BayardNewsletterORMBundle:Newsletter', 'n')
            ->innerJoin('n.newsletterType', 'type', Join::WITH, $qb->expr()->andX(
                $qb->expr()->eq('type.name', ':type')
            ))
            ->where('n.open4Id = :id')
            ->setParameters([
                'type' => $newsletterType,
                'id' => $open4Id
            ]);

        return $query->getQuery()->getSingleResult();
    }

    /**
     * Return all Newsletters that should be sent.
     * @return mixed
     */
    public function getNewslettersToSend()
    {
        $qb = $this->createQueryBuilder('n')
            ->andWhere('n.status != :sent')
            ->andWhere('n.status != :abandonned')
            ->andWhere('n.dispatchDate <= :now')
            ->setParameters([
                'abandonned' => 'abandonned',
                'sent' => 'sent',
                'now' => new \DateTime()
            ]);

        return $qb->getQuery()->getResult();

    }

    /**
     * Return all ads that are applicable to given newsletter
     * @param $newsletterId
     * @return mixed
     */
    public function getApplicableAds($newsletterId)
    {
        $newsletter = $this->find($newsletterId);

        $qb = $this->getEntityManager()->createQueryBuilder();

        $query = $qb->select('a')
            ->from('BayardNewsletterORMBundle:Advertisement', 'a')
            ->innerJoin('a.newsletterTypes', 'type', Join::WITH, $qb->expr()->andX(
                $qb->expr()->eq('type.id', ':type')
            ))
            ->innerJoin('a.dates', 'dates', Join::WITH, $qb->expr()->andX(
                $qb->expr()->lte('dates.beginningDate', ':date'),
                $qb->expr()->gte('dates.endDate', ':date')
            ))
            ->where('a.enabled = 1')
            ->setParameters([
                'type' => $newsletter->getNewsletterType(),
                'date' => $newsletter->getDispatchDate()
            ]);

        return $query->getQuery()->getResult();
    }
}
