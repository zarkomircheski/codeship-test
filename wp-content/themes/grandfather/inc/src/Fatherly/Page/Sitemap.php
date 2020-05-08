<?php

namespace Fatherly\Page;

/**
 * Class Sitemap
 *
 * This class contains methods to assist with the HTML sitemap page.
 * @package Fatherly\Page
 *
 *
 */
class Sitemap
{
    public $endDate;

    public static function init()
    {
        return new self;
    }

    public function __construct()
    {
        $this->startDate = $this->getOldestPostDate();
    }

    public function getMonthsRange()
    {
        $start = new \DateTime($this->startDate);
        $start->modify('first day of this month');
        $end = new  \DateTime();
        $end->modify('first day of next month');
        $interval = \DateInterval::createFromDateString('1 month');
        $period = new \DatePeriod($start, $interval, $end);
        $dates = array();
        foreach ($period as $month) {
            if ($this->hasPosts($month)) {
                $dates[$month->format("Y")][] = $month->format("Y-F");
            }
        }
        return array_reverse($dates, true);
    }


    protected function getOldestPostDate()
    {
        $queryArgs = array(
            'offset' => 0,
            'orderby' => 'ASC',
            'order' => 'ASC',
            'post_type' => 'post',
            'post_status' => 'publish',
            'suppress_filters' => true,
            'posts_per_page' => 1
        );
        $post = new \WP_Query($queryArgs);
        return $post->post->post_date;
    }

    protected function hasPosts($date)
    {
        global $wpdb;
        $query = sprintf("SELECT count(*) as post_count from %s WHERE MONTH(post_date) = '%d' AND YEAR(post_date) = %d", $wpdb->posts, $date->format('m'), $date->format('Y'));
        $results = $wpdb->get_results($query);
        if ($results[0]->post_count !== "0") {
            return true;
        } else {
            return false;
        }
    }
}
