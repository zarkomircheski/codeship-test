<?php

namespace Fatherly\Imgix;

/**
 * Class Cropper
 *
 * This code contains a helper class for cropping images using imgix.
 *
 * @package Fatherly\Imgix
 */

class Cropper
{
    public $imgixDomain = "images.fatherly.com";
    public $debug = false;
    public $width;
    public $height;
    public $id;
    public $cropType;
    public $fit;


    public static function init()
    {
        return new self;
    }

    public function __construct()
    {
    }

    public function crop()
    {
        if ($this->debug) {
            return $this->getPlaceholderImage();
        } else {
            return $this->getImgixImage();
        }
    }

    public function debug()
    {
        $this->debug = true;
        return $this;
    }

    public function height($height)
    {
        $this->height = $height;
        return $this;
    }

    public function width($width)
    {
        $this->width = $width;
        return $this;
    }

    public function cropType($cropType)
    {
        $this->cropType = $cropType;
        return $this;
    }

    public function fit($fit)
    {
        $this->fit = $fit;
        return $this;
    }

    public function attachmentId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function postId($id)
    {
        $this->id = get_post_thumbnail_id($id);
        return $this;
    }

    protected function getImgixImage()
    {
        $wpImgUrl = wp_get_attachment_url($this->id, 'full');
        $queryString = $this->generateImgixQueryString();
        $baseUrl = $this->replaceImageUrl($wpImgUrl);
        return $baseUrl . $queryString;
    }

    protected function generateImgixQueryString()
    {
        $qs = '?q=65';
        $qs .= ($this->width && $this->height && !$this->fit) ? "&fit=crop" : "";
        $qs .= ($this->fit) ? "&fit={$this->fit}" : "";
        $qs .= ($this->width && $this->height && $this->cropType != '') ? "&crop={$this->cropType}" : "";
        $qs .= (!$this->fit) ? "&enable=upscale" : "";
        $qs .= ($this->width) ? "&w={$this->width}" : "";
        $qs .= ($this->height) ? "&h={$this->height}" : "";
        return $qs;
    }

    protected function getPlaceholderImage()
    {
        return "https://via.placeholder.com/{$this->width}x{$this->height}";
    }

    protected function notOnImgixDomain($image)
    {
        return strpos($image, $this->imgixDomain) == false;
    }

    protected function replaceImageUrl($image)
    {
        $image = str_replace("www.", "", $image);
        if ($this->notOnImgixDomain($image)) {
            $image = str_replace(array(
                "fatherly.loc",
                "fatherly.com",
                "fatherly.usterix.dev"
            ), $this->imgixDomain, $image);
        }
        return $image;
    }
}
