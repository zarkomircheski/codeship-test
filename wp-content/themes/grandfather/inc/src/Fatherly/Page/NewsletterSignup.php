<?php

namespace Fatherly\Page;

/**
 * Class NewsletterSignup
 *
 * This is the class that provides functionality necessary to power the newsletter signup functionality.
 *
 * @package Fatherly\Page
 */
class NewsletterSignup
{
    /**
     * This is the post id of the current newsletter signup post.
     * @var false|int
     */
    public $postId;


    /**
     * This is the array of custom field values from the newsletter signup post.
     *
     * @var array|bool
     */
    public $customFields;


    /**
     * Once we have determined which if any variant is being accessed we will set this property equal to it's array key
     *
     * @var null
     */
    public $variant = null;

    /**
     * Factory method to make the instantiation of the class cleaner.
     *
     * @return NewsletterSignup
     */
    public static function init()
    {
        return new self;
    }

    /**
     * NewsletterSignup constructor.
     *
     * Set up our default properties such as the id and custom fields.
     * Then kicks of the method to determine the variant.
     *
     */
    public function __construct()
    {
        $this->postId = get_the_id();
        $this->customFields = get_fields($this->postId);
        $this->setVariant();
    }

    /**
     * This method will check to see if we have $_GET params in the url and if one of those params is the key `fth_ref`
     * Should both of those conditions be true we will make a call to get the variant and then set the variant property
     * accordingly.
     */
    protected function setVariant()
    {
        if (isset($_GET) && isset($_GET['fth_ref'])) {
            $key = $this->getVariant($_GET['fth_ref']);
            $this->variant = (isset($key) ? $this->customFields['variants'][$key] : null);
        }
    }

    /**
     * @param $fth_ref
     *
     * This will loop over our array of variant until we find one that has a referral key matching the one provided.
     *
     * @return int|null|string
     */
    protected function getVariant($fth_ref)
    {
        foreach ($this->customFields['variants'] as $key => $val) {
            if ($val['referral_key'] === $fth_ref) {
                return $key;
            }
        }
        return null;
    }

    /**
     * This will check if we're currently accessing a variant and then will also check if that variant has a background
     * image set.
     *
     * If we have no variant then we will return the default image for the signup page and if that is not available then
     * we fall back to the image used on the `/sign-up-custom` page.
     *
     * If we have a variant that has an image then we return the url to the image that is set there. If the variant does
     * not have an image then we return the default image for the newsletter page.
     *
     * @param $size
     *
     * @return string
     */
    public function getBackgroundImage($size)
    {
        if ($size == 'desktop' &&$this->variant && $this->variant['variant_image']) {
            return $this->variant['variant_image']['url'];
        } elseif ($size == 'mobile' &&$this->variant && $this->variant['variant_mobile_image']) {
            return $this->variant['variant_mobile_image']['url'];
        } else {
            if (!empty($this->customFields['default_image']['url'])) {
                return $this->customFields['default_image']['url'];
            } else {
                return "https://images.fatherly.com/wp-content/uploads/2017/01/GettyImages-678751579-2.jpg";
            }
        }
    }

    /**
     *
     * This performs the exact same functionality as `getBackgroundImage()` except on the copy for the page.
     *
     * @return mixed|string
     */
    public function getCopy()
    {
        if ($this->variant && !empty($this->variant['variant_copy'])) {
            return $this->variant['variant_copy'];
        } else {
            if (!empty($this->customFields['default_copy'])) {
                return $this->customFields['default_copy'];
            } else {
                return "<h2>Sign up for tips, tricks, and advice you'll actually use. </h2>";
            }
        }
    }

    /**
     *
     * This method is called by the form on the front-end to set the value of a hidden input field equal to the referral
     * source that we want passed to postup. If there is no referral_key then this will be set to the url of the page.
     * @return mixed
     */
    public function getReferralKey()
    {
        if ($this->variant) {
            $key = $this->variant['referral_key'];
        } else {
            $key = $_SERVER['REQUEST_URI'];
        }

        return filter_var($key, FILTER_SANITIZE_STRING);
    }
}
