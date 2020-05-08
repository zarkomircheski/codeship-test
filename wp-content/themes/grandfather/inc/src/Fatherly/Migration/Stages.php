<?php

namespace Fatherly\Migration;

use Fatherly\Page\Helper as Helper;
use Fatherly\Taxonomy\Stages as StagesTaxHelper;

class Stages
{
    //Slugs of the main categories that terms will be moved into.
    public static $stagesMainTerms = array(
        'toddlers' => array(
            'age-1',
            'year-2',
            'stage-toddler',
        ),
        'big-kids' => array(
            'year-5',
            'year-6',
            'year-7',
            'year-8',
            'stage-kid'
        ),
        'tween-teen' => array(
            'year-9',
            'year-10',
            'year-11',
            'year-12',
            'year-13',
            'year-14',
            'year-15',
            'year-16',
            'year-17',
            'stage-teen',
            'stage-tween',
        ),
        'stage-preschool' => array(
            'year-3',
            'year-4',
        ),

        'stage-baby' => array(
            'month-0',
            'month-1',
            'month-2',
            'month-3',
            'month-4',
            'month-5',
            'month-6',
            'month-7',
            'month-8',
            'month-9',
            'month-10',
            'month-11',
            'month-12',
            'baby_m02',

        ),
        'stage-trying' => array(
            'stage-pregnancy',
            'week-0',
            'week-1',
            'week-2',
            'week-3',
            'week-4',
            'week-5',
            'week-6',
            'week-7',
            'week-8',
            'week-9',
            'week-10',
            'week-11',
            'week-12',
            'week-13',
            'week-14',
            'week-15',
            'week-16',
            'week-17',
            'week-18',
            'week-19',
            'week-20',
            'week-21',
            'week-22',
            'week-23',
            'week-24',
            'week-25',
            'week-26',
            'week-27',
            'week-28',
            'week-29',
            'week-30',
            'week-31',
            'week-32',
            'week-33',
            'week-34',
            'week-35',
            'week-36',
            'week-37',
            'week-38',
            'week-39',
            'week-40',
            'week-41',
            'week-42',
            'week-43',
            'week-44',
            'week-45',
            'week-46',
            'week-47',
            'week-48',
            'week-49',
            'week-50',
            'week-51',
        )
    );
    public $stagesHelper;
    public $stagesRanges;
    public $term;

    public static function init()
    {
        return new self;
    }

    public function __construct()
    {
        $this->stagesHelper = new StagesTaxHelper();
        $this->stagesRanges = $this->stagesHelper->stagesAgeRanges;
    }

    public static function generateInitialTotals()
    {
        foreach (self::$stagesMainTerms as $stagesMainTermSlug => $stagesMainTermTargets) {
            self::$stagesMainTerms[$stagesMainTermSlug]['count'] = 0;
            foreach ($stagesMainTermTargets as $targetTerm) {
                $term = get_term_by('slug', $targetTerm, 'stages');
                self::$stagesMainTerms[$stagesMainTermSlug]['count'] += $term->count;
            }
        }
        return self::$stagesMainTerms;
    }

    public function migrateStagesTerm($term)
    {
        $return = array();
        $termTo = get_term_by('slug', $term, 'stages');
        $targetPostsQuery = $this->getPostsInTarget($term);
        foreach ($targetPostsQuery->posts as $targetPost) {
            wp_set_post_terms($targetPost->ID, $term, 'stages', true);
        }

        $return['count'] = $targetPostsQuery->post_count;
        if ($targetPostsQuery->post_count !== 25) {
            $this->deleteTerms($term);
            while (key(self::$stagesMainTerms) !== $term) {
                next(self::$stagesMainTerms);
            }
            if (next(self::$stagesMainTerms)) {
                $return['nextTerm'] = key(self::$stagesMainTerms);
                $return['more'] = true;
            } else {
                $return['more'] = false;
            }
        } else {
            $return['more'] = true;
        }

        return $return;
    }

    public function deleteTerms($term)
    {
        $mainTermTargets = self::$stagesMainTerms[$term];
        foreach ($mainTermTargets as $target) {
            $term = get_term_by('slug', $target, 'stages');
            wp_delete_term($term->term_id, 'stages');
        }
    }

    public function getPostsInTarget($term)
    {
        $termTo = get_term_by('slug', $term, 'stages');
        $mainTermTargets = self::$stagesMainTerms[$term];
        $targetIds = array();
        foreach ($mainTermTargets as $target) {
            $term = get_term_by('slug', $target, 'stages');
            $targetIds[] = $term->term_id;
        }
        $args = array(
            'no_found_rows' => true,
            'posts_per_page' => 25,
            'post_type' => 'post',
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'stages',
                    'field' => 'term_id',
                    'terms' => $termTo->term_id,
                    'operator' => 'NOT IN',
                    'include_children' => false
                ),
                array(
                    'taxonomy' => 'stages',
                    'field' => 'term_id',
                    'terms' => $targetIds,
                    'operator' => 'IN',
                    'include_children' => false
                )
            ),

        );
        return new \WP_Query($args);
    }


    public function setupPageDataForMigration()
    {
        global $wpdb;
        $total = $wpdb->get_results("SELECT COUNT(*) as total FROM {$wpdb->prefix}legacy_stage_relations WHERE processed = 0");
        $data = new \stdClass();
        $data->totalRows = $total[0]->total;
        return $data;
    }


    public function migrateLegacyStagesRelationships()
    {
        global $wpdb;

        //Get 10 posts that have not already been processed.
        $rowsToProcess = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}legacy_stage_relations WHERE processed = 0 LIMIT 10");
        //Determine what ages should be applied to the post based off of the slugs.
        foreach ($rowsToProcess as $rowToProcess) {
            $terms = array();
            if (strpos($rowToProcess->stages_slugs, ',') === false) {
                $terms[] = $rowToProcess->stages_slugs;
            } else {
                $terms = explode(',', $rowToProcess->stages_slugs);
            }
            $ageRange = $this->determineAgeRangeFromLegacySlugs($terms);
            //Set the determined ages on the post and update the post
            $this->updatePostAgeRange($rowToProcess->post_id, $ageRange);
            // Set the value of the row to processed in the legacy relation table.
            $wpdb->update("{$wpdb->prefix}legacy_stage_relations", array('processed' => 1), array('post_id' => $rowToProcess->post_id));
        }
        // Return data back to page for user feedback
        $return = new \stdClass();
        $return->count = count($rowsToProcess);
        $return->more = (count($rowsToProcess) < 10) ? false : true;
        return $return;
    }

    protected function updatePostAgeRange($postID, $ageRange)
    {
        if (count($ageRange) > 0) {
            $start = reset($ageRange);
            $end = end($ageRange);
            update_field('age_range_from', $start, $postID);
            update_field('age_range_to', $end, $postID);
        } else {
            //This means that the terms we looked in to establish the age didnt match which means its something not mapped to an age range
            update_field('not_applicable', true, $postID);
        }
    }

    protected function determineAgeRangeFromLegacySlugs($slugs)
    {
        $ages = array();
        foreach ($slugs as $slug) {
            switch ($slug) {
                /**
                 * If the slug contains the word "week" then it's automatically going to be -1 in the range.
                 * This is because these terms reference before birth stages.
                 */
                case strpos($slug, "week-") !== false:
                    $ages[] = -1;
                    break;
                /**
                 * If the slug contains the word "month" then it's automatically going to be 0 in the range.
                 * This is because this represents time after the birth of a child up to but not exceeding one year.
                 */
                case strpos($slug, "month-") !== false:
                    $ages[] = 0;
                    break;
                case strpos($slug, 'stage-') !== false:
                    /**
                     * If the slug contains the word "stage" then we need to get the age range for that stage or for the stage
                     * that the stage was moved into and then push those ages onto the array.
                     */

                    if (array_key_exists($slug, self::$stagesMainTerms)) {
                        $this->term = $slug;
                    } else {
                        foreach (self::$stagesMainTerms as $parent => $sub) {
                            if (in_array($slug, $sub)) {
                                $this->term = $parent;
                                break;
                            }
                        }
                    }
                    $ageRange = $this->stagesRanges[$this->term];
                    $ages[] = $ageRange['start'];
                    $ages[] = $ageRange['end'];
                    break;
                case strpos($slug, "age-") !== false || strpos($slug, "year-") !== false:
                    /**
                     * If the slug contains the word "age" or "year" then we will explode that string on the "-" character and
                     * then get the last value which will be the number.
                     */
                    $parts = explode('-', $slug);
                    $ages[] = intval($parts[1]);
                    break;
                default:
                    /**
                     * If we get to here then that mean that the slug doesnt have the word 'stage' or that it's not
                     * in the other formats we're looking for. Additionally an items slug can be nonsense and not
                     * be something we can match.
                     *
                     * We can essentially just re-run our matcher from earlier and it will catch anything that can be
                     * matched.
                     */
                    $matched = false;
                    if (array_key_exists($slug, self::$stagesMainTerms)) {
                        $this->term = $slug;
                        $matched = true;
                    } else {
                        foreach (self::$stagesMainTerms as $parent => $sub) {
                            if (in_array($slug, $sub)) {
                                $this->term = $parent;
                                $matched = true;
                                break;
                            }
                        }
                    }
                    if ($matched === true) {
                        $ageRange = $this->stagesRanges[$this->term];
                        $ages[] = $ageRange['start'];
                        $ages[] = $ageRange['end'];
                    }
                    break;
            }
        }
        return array_values(array_unique($ages, SORT_NUMERIC));
    }
}
