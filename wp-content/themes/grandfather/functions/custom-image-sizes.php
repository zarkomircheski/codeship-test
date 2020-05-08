<?php

/**
 * LEGACY IMAGE NAMES:
 *
 * | NAME               | WIDTH | HEIGHT | CROP |
 * |--------------------|-------|--------|------|
 * | large-thumb        | 500   | 400    | true |
 * | category-thumb     | 578   | 350    | true |
 * | sidebar_ad_retina  | 600   | 300    | true |
 * | sponsor_logo       | 400   | 80     |      |
 * | popular-large      | 800   | 400    | true |
 * | bt-thumb           | 600   | 363    | true |
 */

/**
 * A filter that removes previously declared image sizes. The filter prevents
 * auto-cropping on upload. We want to keep WordPress running nice and light.
 * WP still crops the sizes 'medium' and 'thumbnail' in order to see
 * previews in admin.
 */
function removeDefaultImageSizes()
{
    $imageSizesToRemove = array(
      'medium_large',
      'large',
      'large-thumb',
      'category-thumb',
      'sidebar_ad_retina',
      'sponsor_logo'
    );
    foreach ($imageSizesToRemove as $size) {
        remove_image_size($size);
    }
}
add_action('init', 'removeDefaultImageSizes');


function fth_img_tag($options = array())
{
    $options['tag'] = true;
    return fth_img($options);
}

/**
 *
 */
function fth_img($options = array())
{

   // Set all the default options
    $defaultOptions = array(
    'classes' => '',           // CSS classes to (tag: true only)
    'alt' => '',               // Alt attribute (tag: true only)
    'post_id' => null,         // post id (pulls featured image)
    'attachment_id' => null,   // attachment id (post_id: null only)
    'width' => null,           // width for crop or resize
    'height' => null,          // height for crop or resize
    'tag' => false,            // returns an <img> tag if true, else just url.
    'retina' => true,          // returns the 2x size. Only if tag = false
    'cropType' => 'smart',    // uses smart crop by default
    'fit' => false,
    );
    // Override the defaults with new options
    $options = array_merge($defaultOptions, $options);

    // If there is no attachment_id or post_id set, check for the global
    // $post object and extract the id from there.
    if ($options['post_id'] == null && $options['attachment_id'] == null) {
        global $post;
        $options['post_id'] = ($post) ? $post->ID : null;
    }

    // Set additional variables for cropping functions.
    $isAttachment = $options['attachment_id'] != null;
    $objectId = (!$isAttachment) ? $options['post_id'] : $options['attachment_id'];

    // Get the cropped image url
    $imgUrl = fth_crop($options['width'], $options['height'], $objectId, $isAttachment, $options['cropType'], $options['fit']);
    $imgUrl2x = fth_crop($options['width'] * 2, $options['height'] * 2, $objectId, $isAttachment, $options['cropType'], $options['fit']);

    // And voila!
    if ($options['tag']) {
        return "<img src=\"{$imgUrl}\" class=\"{$options['classes']}\" alt=\"{$options['alt']}\" srcset=\"{$imgUrl} 1x, {$imgUrl2x} 2x\" />";
    } else {
        return $options['retina'] ? $imgUrl2x : $imgUrl;
    }
}


function fth_crop($width, $height, $id, $isAttachmentId, $cropType, $fit)
{
    if (get_field('imgix_enabled', 'option')) {
        return fth_imgix_crop($width, $height, $id, $isAttachmentId, $cropType, $fit);
    } else {
        return fth_legacy_crop($width, $height, $id, $isAttachmentId);
    }
}

/**
 *
 */
function fth_imgix_crop($width = null, $height = null, $id = 0, $isAttachmentId = false, $cropType = 'smart', $fit = false)
{
    if ($isAttachmentId) {
        return Fatherly\Imgix\Cropper::init(get_field('image_cdn_domain', 'option'))->attachmentId($id)->height($height)
            ->width($width)->cropType($cropType)->fit($fit)->crop();
    } else {
        return Fatherly\Imgix\Cropper::init(get_field('image_cdn_domain', 'option'))->postId($id)->height($height)
            ->width($width)->cropType($cropType)->fit($fit)->crop();
    }
}


/**
 *
 */
function fth_legacy_crop($width = null, $height = null, $id = 0, $isAttachmentId = false)
{
    $thumb = ($isAttachmentId) ? $id : get_post_thumbnail_id($id);
    $img_url = wp_get_attachment_url($thumb, 'full');
    if ($img_url != false) {
        $image = aq_resize($img_url, $width, $height, true, true, true);
    } else {
        $image = '';
    }
    return (!empty($image)) ? $image : $img_url;
}
