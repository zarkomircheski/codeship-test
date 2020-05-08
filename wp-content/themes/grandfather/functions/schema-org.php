<?php
/**
 * JSON-encodes the object and wraps it in script tags
 * @param  array $obj The array to be encoded
 * @return string      Json object wrapped in a script tag
 */
function fth_schema_render_json($obj)
{
    $output = '<script type="application/ld+json">';
    $output .= wp_json_encode($obj);
    $output .= '</script>';
    return $output;
}


function fth_schema_generate_root_object($type = '')
{
    $obj = array(
        '@context' => 'http://schema.org',
    );
    if (!empty($type)) {
        $obj['@type'] = $type;
    }

    return $obj;
}


/**
 * Site data for basic Schema.org use
 * @return array
 */
function fth_schema_generate_site_data()
{
    $obj = array();

    $obj['@type']       = "Organization";
    $obj['name']        = "Fatherly";
    $obj['description'] = "Fatherly is a leading digital resource for new parents, combining practical advice and evidence-based insights from top experts around the world.";
    $obj['url']         = "https://www.fatherly.com";
    $obj['logo']        = fth_schema_generate_image_data(23774);

    return $obj;
}


/**
 * [fth_schema_generate_image_data description]
 * @param  [type]  $imageId  [description]
 * @param  boolean $isPostId [description]
 * @return [type]            [description]
 */
function fth_schema_generate_image_data($imageId, $isPostId = false)
{
    $imageId = $isPostId ? (int) get_post_thumbnail_id($imageId) : $imageId;

    $obj = array();
    $img = wp_get_attachment_image_src($imageId, 'full');

    $obj['@type'] = 'ImageObject';
    $obj['contentUrl'] = $img[0];
    $obj['width'] = $img[1].'px';
    $obj['height'] = $img[2].'px';
    $obj['url'] = $img[0];

    return $obj;
}

function fth_schema_organization_tags()
{
    $obj = array_merge(fth_schema_generate_root_object(), fth_schema_generate_site_data());

    $obj['email']       = 'hello@fatherly.com';
    $obj['sameAs']      = array(
        "https://www.facebook.com/FatherlyHQ/",
        "https://twitter.com/fatherlyhq",
        "https://www.instagram.com/Fatherly/",
        "https://plus.google.com/+FatherlyHQ/",
        "https://www.linkedin.com/company/fatherly",
        "https://www.youtube.com/channel/UC-PfbmXWqUYO_UCKP08LKDA/featured"
    );

    echo fth_schema_render_json($obj);
}
add_action('schema_org', 'fth_schema_organization_tags');




function fth_schema_breadcrumb_tags()
{
    $itemListElement = array();
    $bc = fth_schema_generate_root_object('BreadcrumbList');

    $root = array(
        '@type' => 'ListItem',
        'position' => 1,
        'item' => array(
            '@id'  => get_home_url(),
            'name' => 'Home'
        )
    );
    $itemListElement[] = $root;


    if (is_single() || is_archive()) {
        $term = false;
        $tax = array(
            '@type' => 'ListItem',
            'position' => 2,
        );
        if (is_single()) {
            $cat = new WPSEO_Primary_Term('category', get_the_ID());
            $cat = $cat->get_primary_term();
            $term = get_category($cat);
        } elseif (is_archive()) {
            $queried_object = get_queried_object_id();
            $term = get_term($queried_object);
        }
        if ($term) {
            if (isset($term->term_id)) {
                $tax['item'] = array(
                    '@id' => get_category_link($term->term_id),
                    'name' => $term->name
                );
                $itemListElement[] = $tax;
            }
        }
    }


    if (is_single()) {
        $post = array(
            '@type' => 'ListItem',
            'position' => 3,
            'item' => array(
                '@id' => get_permalink(),
                'name' => get_the_title()
            )
        );
        $itemListElement[] = $post;
    }

    $bc['itemListElement'] = $itemListElement;

    if (is_single() || is_archive()) {
        echo fth_schema_render_json($bc);
    }
}
add_action('schema_org', 'fth_schema_breadcrumb_tags');

function fth_schema_article_tags()
{
    if (is_single()) {
        date_default_timezone_set('America/New_York');
        $obj = fth_schema_generate_root_object('Article');
        $obj['mainEntityOfPage'] = array(
            '@type' => 'WebPage',
            '@id'   => get_permalink()
        );
        $obj['headline'] = get_the_title();
        $obj['image'] = fth_schema_generate_image_data(get_the_ID(), true);
        $obj['datePublished'] = get_the_date('c', get_the_ID());
        $obj['dateModified'] = get_the_modified_date('c');
        $obj['author'] = array(
            '@type' => 'Person',
            'name'  => get_the_author_meta('display_name')
        );
        $obj['publisher'] = fth_schema_generate_site_data();

        global $post;
        $terms = wp_get_post_terms($post->ID, 'fields');
        $fields = array();
        if (isset($terms) && is_array($terms) && !empty($terms)) {
            foreach ($terms as $term) {
                array_push($fields, $term->name);
            }
        }

        $tags = wp_get_post_tags($post->ID);
        if (isset($tags) && is_array($tags) && !empty($tags)) {
            foreach ($tags as $tag) {
                $fields[] = $tag -> name;
            }
        }
        
        $obj['keywords'] = $fields;

        //returns a string of the last top level category in the array, or 'uncategorized' if there isnt one.
        $obj['articleSection'] = isset(get_the_category()[0]) ? array_reduce(get_the_category(), function ($previous, $cat) {
            if ($cat->parent == 0) {
                return $cat->name;
            } else {
                return $previous;
            }
        }, 'uncategorized') : 'uncategorized';

        echo fth_schema_render_json($obj);
    }
}
add_action('schema_org', 'fth_schema_article_tags');
