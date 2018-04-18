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
    private $s3;
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
        $s3,
        $bucket,
        $newslettersLocation
    ) {
        $this->entityManager = $em;
        $this->bucket = $bucket;
        $this->newslettersLocation = $newslettersLocation;
        $this->s3 = $s3;
    }

    /**
     * Insert subscriber conditions into newsletter
     *
     * @param $crawler
     * @return string - the html content of the newsletter
     */
    public function insertTargetingConditions($htmlContent)
    {
        $htmlContent = str_replace(
            '<!-- IsSubscribedBegin -->',
            "<% if(targetData.abonne== '1'){ %><!-- IsSubscribedBegin -->",
            $htmlContent
        );

        $htmlContent = str_replace(
            '<!-- IsNotSubscribedBegin -->',
            "<% if(targetData.abonne== '2'){ %><!-- IsNotSubscribedBegin -->",
            $htmlContent
        );

        $htmlContent = str_replace(
            '<!-- IsSubscribedEnd -->',
            "<!-- IsSubscribedEnd --><% } %>",
            $htmlContent
        );

        $htmlContent = str_replace(
            '<!-- IsNotSubscribedEnd -->',
            "<!-- IsNotSubscribedEnd --><% } %>",
            $htmlContent
        );

        $htmlContent = str_replace(
            '<!-- insertMirrorPage -->',
            '<%@ include view="bay_NewsAuto_MirrorPage"%>',
            $htmlContent
        );

        $htmlContent = str_replace(
            '<!-- insertFooter -->',
            '<%@ include view="bay_NewsAuto_Unsub_CrxAlert" %>',
            $htmlContent
        );

        return $htmlContent;

    }

    /**
     * @param $newsletterEntity
     * @param $advertisementEntities
     * @param null $logger
     * @return mixed
     */
    public function insertAdvertisements(
        $newsletterEntity,
        $advertisementEntities,
        $isForNeolane = false,
        $logger = null
    ) {
        $htmlFile = $this->s3->getObject([
            'Bucket' => $this->bucket,
            'Key' => $newsletterEntity->getHtmlLocation()
        ]);

        $crawler = new HtmlPageCrawler((string)$htmlFile['Body']);

        $htmlContent = $crawler->saveHTML();

        foreach ($advertisementEntities as $ad) {
            if ($logger !== null) {
                $logger->info('[' . date(DATE_ISO8601) . '] Advertisement at position ' . $ad->getPosition());
            }
            if ($crawler->filter('.ad-' . $ad->getPosition())->count() > 0) {
                $htmlContent = $this->insertAdvertisement($ad, $htmlContent, $ad->getPosition(), $isForNeolane);
            } else {
                $pos = $newsletterEntity->getNbPositions();
                if ($newsletterEntity->getNewsletterType() == "urbi")
                    $pos++;
                $htmlContent = $this->insertAdvertisement($ad, $htmlContent, $pos, $isForNeolane);
            }
        }
        //@todo minify html
        if ($isForNeolane) {
            $htmlContent = $this->insertTargetingConditions($htmlContent);

            $this->s3->putObject(array(
                'Bucket' => $this->bucket,
                'Key' => $newsletterEntity->getHtmlLocation(),
                'ContentType' => 'text/html',
                'Body' => $htmlContent,
                'ACL' => 'public-read',
                'StorageClass' => 'STANDARD',
            ));
        }
        return str_replace("\n", '', $htmlContent);
    }

    /**
     * @param $ad
     * @param $htmlContent
     * @param $position
     * @param $targetSelector
     * @param $isForNeolane
     * @return string
     */
    public function insertAdvertisement($ad, $htmlContent, $position, $isForNeolane)
    {
        $adContent =
            "<table class=\"row advertisement hiddenAd ad-1 no-padding-left no-padding-right\" style=\"border-collapse: collapse;border-spacing: 0;display: table;padding: 0;padding-left: 0!important;padding-right: 0!important;position: relative;text-align: left;vertical-align: top;width: 100%;\">" .
            "<tbody style=\"padding-left:0!important;padding-right:0!important\"><tr style=\"padding:0;padding-left:0!important;padding-right:0!important;text-align:left;vertical-align:top\">" .
            "<th class=\"small-12 large-8 columns first last\" style=\"Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:0!important;padding-right:0!important;text-align:left;width:370.67px\">" .
            "<table style=\"border-collapse:collapse;border-spacing:0;padding:0;padding-left:0!important;padding-right:0!important;text-align:left;vertical-align:top;width:100%\">" .
            "<tbody><tr style=\"padding:0;padding-left:0!important;padding-right:0!important;text-align:left;vertical-align:top\">" .
            "<th style=\"Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0;padding-left:0!important;padding-right:0!important;text-align:left\">" .
            "<p class=\"e-pub-surtitle\" style=\"Margin:0;Margin-bottom:10px;color:#adadad;font-family:Helvetica,Arial,sans-serif!important;font-size:11px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left;text-transform:uppercase\">Publicité</p>" .
            "<a style=\"Margin:0;color:#2199e8;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.3;margin:0;padding:0;padding-left:0!important;padding-right:0!important;text-align:left;text-decoration:none\" href=\"" . $ad->getRedirectURL() . "\">" .
            "<img alt=\"\" style=\"-ms-interpolation-mode:bicubic;border:none;clear:both;display:block;max-width:100%;outline:0;padding-left:0!important;padding-right:0!important;text-decoration:none;width:auto\" src=\"" . $ad->getImageLink() . "\">" .
            "</a></th></tr></tbody></table></th></tr></tbody></table>";

        if ($isForNeolane) {
            switch ($ad->getTarget()) {
                case 'subscribers':
                    $adContent = '<% if(targetData.abonne == \'1\') { %>' . $adContent . '<% } %>';
                    break;
                case 'not_subscribers':
                    $adContent = '<% if(targetData.abonne == \'2\') { %>' . $adContent . '<% } %>';
                    break;
            }
        }

        return str_replace(
            '<div class="ad-' . $position . '">',
            '<div class="ad-' . $position . '">' . $adContent,
            $htmlContent
        );
    }
}