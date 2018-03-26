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

    public function insertAdvertisements($newsletterEntity, $advertisementEntities){
        $htmlFile = $this->s3->getObject([
            'Bucket' => $this->bucket,
            $newsletterEntity->getXmlLocation()
        ]);


    }

}