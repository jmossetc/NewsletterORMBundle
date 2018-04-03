<?php
/**
 * Created by PhpStorm.
 * User: jmossetc
 * Date: 26/03/18
 * Time: 17:38
 */

namespace Bayard\NewsletterORMBundle\Services;

use Aws\S3\S3Client;
use Aws\Sqs\SqsClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DomCrawler\Crawler;
use \Wa72\HtmlPageDom\HtmlPageCrawler;

class AdvertisementsManager
{
    protected $s3;
    protected $sqs;
    protected $bucket;
    protected $newslettersLocation;
    protected $entityManager;

    /**
     * AdvertisementsManager constructor.
     * @param EntityManagerInterface $em
     * @param $version
     * @param $region
     * @param $key
     * @param $secret
     * @param $bucket
     * @param $newslettersLocation
     */
    public function __construct(
        EntityManagerInterface $em,
        $version,
        $region,
        $key,
        $secret,
        $bucket,
        $newslettersLocation
    ) {
        $this->entityManager = $em;
        $this->bucket = $bucket;
        $this->newslettersLocation = $newslettersLocation;
        $this->s3 = new S3Client([
            'version' => $version,
            'region' => $region,
            'credentials' => [
                'key' => $key,
                'secret' => $secret,
            ],
        ]);
        $this->sqs = new SqsClient([
            'version' => $version,
            'region' => $region,
            'credentials' => [
                'key' => $key,
                'secret' => $secret,
            ],
        ]);
    }

    /**
     * @param $newsletterEntity
     * @param $advertisementEntities
     * @param null $logger
     * @return mixed
     */
    public function insertAdvertisements($newsletterEntity, $advertisementEntities, $logger = null)
    {
        $htmlFile = $this->s3->getObject([
            'Bucket' => $this->bucket,
            'Key' => $newsletterEntity->getHtmlLocation()
        ]);

        $crawler = new HtmlPageCrawler((string)$htmlFile['Body']);

        $crawler->filter('.advertisement')->css('display', 'none');
        $crawler->filter('.advertisement a')->removeAttr('href');
        $crawler->filter('.advertisement img')->removeAttr('src');

        foreach ($advertisementEntities as $ad) {
            if ($logger !== null) {
                $logger->info('[' . date(DATE_ISO8601) . '] Advertisement at position ' . $ad->getPosition());
            }/*
            if ($crawler->filter('.advertisement.ad-' . $ad->getPosition())->count() > 0) {
                $this->insertAdvertisement($ad, $crawler, $ad->getPosition());
            } else {
                $this->insertAdvertisement($ad, $crawler, $newsletterEntity->getNbPositions());
            }*/
        }
        $this->s3->putObject(array(
            'Bucket' => $this->bucket,
            'Key' => $newsletterEntity->getHtmlLocation(),
            'ContentType' => 'text/html',
            'Body' => $crawler->saveHTML(),
            'ACL' => 'public-read',
            'StorageClass' => 'STANDARD',
        ));

        return $crawler->saveHTML();
    }

    /**
     * @param $ad
     * @param $crawler
     * @param $position
     */
    public function insertAdvertisement($ad, $crawler, $position)
    {
        $crawler->filter('.advertisement.ad-' . $position)->css('display', 'table');

        $crawler->filter('.advertisement.ad-' . $position . ' a')
            ->setAttribute('href', $ad->getRedirectURL());
        $crawler->filter('.advertisement.ad-' . $ad->getPosition() . ' img')
            ->setAttribute('src', $ad->getImageLink());
    }


}