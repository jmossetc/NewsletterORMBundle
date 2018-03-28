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

    public function insertAdvertisements($newsletterEntity, $advertisementEntities, $logger = null)
    {
        $htmlFile = $this->s3->getObject([
            'Bucket' => $this->bucket,
            'Key' => $newsletterEntity->getHtmlLocation()
        ]);

        $crawler = new HtmlPageCrawler((string)$htmlFile['Body']);

        $crawler->filter('.advertisement.essentiel > a')->removeAttr('href');
        $crawler->filter('.advertisement.essentiel > img')->removeAttr('src');

        dump($advertisementEntities);

        foreach ($advertisementEntities as $ad) {
            dump($ad);
            $logger->info('[' . date(DATE_ISO8601) . '] Advertisement at position ' . $ad->getPosition());
            if ($crawler->filter('.advertisement.essentiel.ad-' . $ad->getPosition())->count() > 0) {
                $style = $crawler->filter('.advertisement.essentiel.ad-' . $ad->getPosition())->getStyle();
                $style = str_replace("display:none!important;", "", $style);
                $crawler->filter('.advertisement.essentiel.ad-' . $ad->getPosition())->setStyle($style);

                $crawler->filter('.advertisement.essentiel.ad-' . $ad->getPosition() . ' > a')
                    ->setAttribute('href', $ad->getRedirectURL());
                $crawler->filter('.advertisement.essentiel.ad-' . $ad->getPosition() . ' > a > img')
                    ->setAttribute('href', $ad->getImageLink());
            } elseif ($logger !== null) {
                $logger->info(
                    '[' .
                    date(DATE_ISO8601) .
                    '] Advertisement of Id ' .
                    $ad->getId() .
                    'cannot be inserted into newsletter of Id ' .
                    $newsletterEntity->getId() .
                    ' because the position' .
                    $ad->getPosition() .
                    ' does not exist'
                );
            }
        }
        $result = $this->s3->putObject(array(
            'Bucket' => $this->bucket,
            'Key' => $newsletterEntity->getHtmlLocation(),
            'ContentType' => 'text/html',
            'Body' => $crawler->saveHTML(),
            'ACL' => 'public-read',
            'StorageClass' => 'STANDARD',
        ));
    }


}