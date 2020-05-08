<?php

namespace Fatherly\Imgix;

/**
 * Class Helper
 *
 * This class contains all the code for the integration with imgix.
 *
 * @package Fatherly\Imgix
 */

class Helper
{
    /**
     * The domain (excluding schema) of the CNAME record that is
     * forwarded to imgix.
     *
     * @var string
     */
    public $imgixDomain = "images.fatherly.com";

    /**
     * The content passed via the WordPress hook "the_content"
     *
     * @var string
     */
    public $content;

    /**
     * This is the factory method for this class its purpose in this instance
     * is purely aesthetics for when its called.
     *
     * @return \Fatherly\Imgix\Helper
     */
    public static function init($CDNDomain)
    {
        return new self($CDNDomain);
    }

    /**
     * This method sets up the filters that we need to hook into from WordPress
     * to perform our changes.
     *
     * Helper constructor.
     */
    public function __construct($CDNDomain)
    {
        $this->imgixDomain = $CDNDomain;
        add_filter(
            'pre_option_upload_url_path',
            array($this, 'updateContentUrl')
        );
        add_filter(
            'the_content',
            array($this, 'updateContentImageUrls'),
            10,
            1
        );
    }

    /**
     * This method sets the default upload image path for media to the Imgix
     * CNAME. An important caveat is that this only applies to images that arent
     * added in the content of a page or post through the WYSIWYG.
     *
     * @return string
     */
    public function updateContentUrl()
    {
        return sprintf("https://%s/wp-content/uploads", $this->imgixDomain);
    }

    /**
     * This method will take in the content provided from the hook "the_content"
     * and search for any image tags within that are hosted locally on fatherly
     * and will then update those urls to the imgix CNAME records and return the
     * updated content.
     *
     * @param $content string
     *
     * @return mixed
     */
    public function updateContentImageUrls($content)
    {
        $this->content = $content;
        $images        = $this->findAllImageTags();
        if ($images) {
            foreach ($images as $key => $image) {
                if (strpos($image, '.gif')) {
                } else {
                    // is the image hosted on fatherly?
                    if ($this->isHostedLocally($image)) {
                        // we need to remove the www from the url and then update the url
                        $image = $this->replaceImageUrlsInContent($image);
                    }
                    // If the image already has srcset or doesn't have an ID in it's class, just return the original image tag
                    if (strpos($image, 'srcset') === false && strpos($image, 'wp-image-') !== false) {
                        // add srcset attribute
                        $image = $this->addSrcsetAndSizes($image);
                    }
                }

                //Then save that to the content
                $this->content = str_replace(
                    $images[$key],
                    $image,
                    $this->content
                );
            }
        }

        return $this->content;
    }

    /**
     * This method will search through the content string and perform a regular
     * expression match to get all of the image tags that exist within the
     * content. It will then return those image tags if there any present. If
     * there are no image tags present then this method will return false.
     *
     * @return bool
     */
    protected function findAllImageTags()
    {
        if (preg_match_all('/<img[^>]+>/ims', $this->content, $matches)) {
            return $matches[0];
        } else {
            return false;
        }
    }

    /**
     * This method will check if an image is hosted locally on fatherly.
     *
     * @param $image
     *
     * @return bool
     */
    protected function isHostedLocally($image)
    {
        $pattern = '/www.fatherly.com|fatherly.com|fatherly.usterix.dev|fatherly2016.staging.wpengine.com/';
        $url     = $this->imageUrlFromImgTag($image);

        if (preg_match(
            '/images.fatherly.com/',
            $url,
            $alreadyConverted
        ) != 1
        ) {
            return (preg_match(
                $pattern,
                $this->imageUrlFromImgTag($image),
                $localUrl
            ) == 1);
        } else {
            return false;
        }
    }

    /**
     * This method will take an image tag and perform a regular expression
     * match and return the url that is inside of the "src" tag.
     *
     * @param $image
     *
     * @return mixed
     */
    protected function imageUrlFromImgTag($image)
    {
        preg_match(
            '/^<\s*img[^>]+src\s*=\s*(["\'])(.*?)\1[^>]*>$/',
            $image,
            $matches
        );

        return $matches[2];
    }

    /**
     * This method will replace any occurrences of local urls found within the
     * src tag on images within the content and replace it with the imgix CNAME.
     *
     * @param $image
     *
     * @return mixed
     */
    protected function replaceImageUrlsInContent($image)
    {
        $image = str_replace("www.", "", $image);
        return  preg_replace(array('/\/\/(fatherly\.com).*?/','/\/\/(staging\.fatherly\.com).*?/','/\/\/(fatherly\.usterix\.dev).*?/'), '//'.$this->imgixDomain, $image);
    }

    /**
     * This method will look to see if the image width is greater than or equal to 600px.
     * If it is, it will add the appropriate srcset/sizes attributes along
     * with the original class and alt attributes.
     *
     * @param $image
     *
     * @return mixed
     */

    protected function addSrcsetAndSizes($image)
    {
        preg_match('/width="(.*?)"/', $image, $imageWidth);
        if ($imageWidth[1] >= 600) {
            preg_match('/wp-image-([0-9]+)/i', $image, $imageId);
            preg_match_all('/(class|alt)=("[^"]*")/i', $image, $imageAtts);
            $newImageAtts = array_combine($imageAtts[1], $imageAtts[2]);
            $newImageAtts['src'] = fth_img(array('attachment_id' => $imageId[1], 'width' => 600, 'retina' => false));
            $newImageAtts['srcset'] = fth_img(array('attachment_id' => $imageId[1], 'width' => 600, 'retina' => true)) . " 1200w, " .
                fth_img(array('attachment_id' => $imageId[1], 'width' => 800, 'retina' => false)) . " 800w, " .
                fth_img(array('attachment_id' => $imageId[1], 'width' => 600, 'retina' => false)) . " 600w, " .
                fth_img(array('attachment_id' => $imageId[1], 'width' => 400, 'retina' => false)) . " 400w";

            return "<img src=\"{$newImageAtts['src']}\" class={$newImageAtts['class']} alt={$newImageAtts['alt']} srcset=\"{$newImageAtts['srcset']}\" sizes=\"(max-width: 600px) 100vw, 600px\" />";
        }
        return $image;
    }
}
