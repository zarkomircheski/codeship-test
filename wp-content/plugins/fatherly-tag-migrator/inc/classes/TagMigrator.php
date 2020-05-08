<?php


/**
 * Class TagMigrator
 */
class TagMigrator
{
    /**
     * @var int $batchSize This is the size of the batch used when processing records.
     */
    public $batchSize = 10;

    /**
     * @var array $batchResults Contains the return data from the batch operation which will be returned to the front-end
     */
    public $batchResults = array();

    /**
     * @var string $tableName The name of the database table where the migration data is stored
     */
    private $tableName;

    /**
     * getAdminPageData
     *
     * This method is called during the creation of the admin page for the plugin and returns the count for how many
     * records of each type still need to be processed. This information is used during progress updates.
     *
     * @return mixed|string|void
     */
    public static function getAdminPageData()
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'tag_migrator';
        $query = "
                SELECT
                  a.action as action,
                  (SELECT count(*)
                   FROM $tableName
                   WHERE processed = 0 AND action = a.action) as total
                FROM $tableName AS a
                GROUP BY action;
        ";
        return json_encode($wpdb->get_results($query));
    }

    /**
     * TagMigrator constructor.
     */
    public function __construct()
    {
        global $wpdb;
        $this->tableName = $wpdb->prefix . 'tag_migrator';
    }

    /**
     * init
     *
     * This is a simple factor method for the TagMigrator class
     *
     * @return TagMigrator
     */
    public static function init()
    {
        return new self;
    }


    /**
     * runMigration
     *
     * This method is called by the ajax handler for the tag migrator and will check the type of migration that needs
     * to be ran and then pass off the request to the appropriate method for that migration type. This method will then
     * return the $batchResults to the front end.
     *
     * @param $type migration type
     * @return array
     */
    public function runMigration($type)
    {
        switch ($type) {
            case 'startRemoveMigration':
                $this->runRemoveOnlyMigrationBatch();
                break;
            case 'startRemoveRedirectMigration':
                $this->runRemoveAndRedirectMigrationBatch();
                break;
            case 'startRemoveRedirectStageMigration':
                $this->runRemoveAndRedirectStageMigrationBatch();
                break;
            case 'startMergeMigration':
                $this->runMergeMigrationBatch();
                break;
        }
        return $this->batchResults;
    }


    /**
     * runRemoveOnlyMigrationBatch
     *
     * This will run a migration action on the `remove_only` migration records. It will load a batch of records and
     * then delete those tags from WP and then update that migration record to processed and set its results in the
     * the `$batchResults` property.
     */
    public function runRemoveOnlyMigrationBatch()
    {
        $migrationRecords = $this->getMigrationRecordsByType('remove_only');

        foreach ($migrationRecords as $migrationRecord) {
            if ($this->removeTagByID($migrationRecord->term_taxonomy_id)) {
                $update = $this->setRecordToProcessed($migrationRecord->id);
                if ($update === false) {
                    $success = false;
                    break;
                }
            } else {
                $success = false;
                break;
            }

        }
        $this->batchResults = array(
            'type_dom' => 'remove_only',
            'type_id' => 'startRemoveMigration',
            'size' => count($migrationRecords),
            'success' => $success ?: true
        );

    }

    /**
     * runRemoveAndRedirectMigrationBatch
     *
     * This will run a migration action on the `remove_and_redirect` migration records. It will load a batch of records
     * and will will then load the `category` that the tag should be redirected to and will then load all of the posts
     * that currently have the target tag attached but do not have the target category. We will then update each of
     * these posts by assigning this category to them and then we will setup a redirect in Yoast for the target tag
     * page to the target category page and then update that migration record to processed and set its results in the
     * `$batchResults` property.
     */
    public function runRemoveAndRedirectMigrationBatch()
    {
        $migrationRecords = $this->getMigrationRecordsByType('remove_and_redirect');
        foreach ($migrationRecords as $migrationRecord) {
            $tagFrom = get_tag($migrationRecord->term_taxonomy_id);
            $categoriesToSlugs = explode(",", $migrationRecord->target_name);
            $categoriesToObjects = array();
            foreach ($categoriesToSlugs as $categorySlug) {
                $categoriesToObjects[] = get_category_by_slug($categorySlug);
            }
            $categoriesToIds = wp_list_pluck($categoriesToObjects, 'term_id');
            if ($migrationRecord->target_name) {
                $postsToMerge = $this->getPostsFromTagAndCategoryForMerge($tagFrom->term_id, $migrationRecord->target_name);
                foreach ($postsToMerge as $postToMerge) {
                    wp_set_post_terms($postToMerge->ID, $categoriesToIds, 'category', true);
                }
                $this->removeAndRedirectTag($tagFrom, $categoriesToObjects[0]);
            } else {
                /*
                 * There is one record that is not a typical migration. This record is for the tag `sponsored` the goal
                 * here is to just ensure that all the posts in that tag have the field `sponsored` set to true and if
                 * not then we need to update it so that it is. Once we complete that we remove the tag like normal.
                 */
                $postsToUpdate = $this->getPostsFromTag($tagFrom->term_id);
                foreach ($postsToUpdate as $postToUpdate) {
                    $customFields = get_fields($postToUpdate->ID);
                    if (!$customFields['sponsored']) {
                        update_field('sponsored', true, $postToUpdate->ID);
                    }
                }
                $this->removeTagByID($tagFrom->term_id);
            }

            $update = $this->setRecordToProcessed($migrationRecord->id);
            if ($update === false) {
                $success = false;
                break;
            }
        }
        $this->batchResults = array(
            'type_dom' => 'remove_and_redirect',
            'type_id' => 'startRemoveRedirectMigration',
            'size' => count($migrationRecords),
            'success' => $success ?: true
        );
    }

    /**
     * runRemoveAndRedirectStageMigrationBatch
     *
     * This will run a migration action on the `remove_and_redirect_stage` migration records. It will load a batch of
     * records and will will then load the `stage` that the tag should be redirected to and will then load all of the
     * posts that currently have the target tag attached but do not have the target stage. We will then update each of
     * these posts by assigning this target stage to them as well as assigning the correct age range to the post for
     * that target stage as well. Then we will setup a redirect in Yoast for the target tag page to the target category
     * page and then update that migration record to processed and set its results in the`$batchResults` property.
     */
    public function runRemoveAndRedirectStageMigrationBatch()
    {
        $migrationRecords = $this->getMigrationRecordsByType('remove_and_redirect_stage');
        foreach ($migrationRecords as $migrationRecord) {
            $tagFrom = get_tag($migrationRecord->term_taxonomy_id);
            $stageTo = get_term_by('slug', $migrationRecord->target_name, 'stages');
            $ageFrom = (int)get_field('age_range_from', 'stages_' . $stageTo->term_id);
            $ageTo = (int)get_field('age_range_to', 'stages_' . $stageTo->term_id);
            $postsToMerge = $this->getPostsFromTagAndStageForMerge($tagFrom->term_id, $migrationRecord->target_name);
            foreach ($postsToMerge as $postToMerge) {
                $this->addStageToPostAndUpdate($postToMerge, $stageTo, $ageFrom, $ageTo);
            }
            $this->removeAndRedirectTag($tagFrom, $stageTo);
            $update = $this->setRecordToProcessed($migrationRecord->id);
            if ($update === false) {
                $success = false;
                break;
            }
        }

        $this->batchResults = array(
            'type_dom' => 'remove_and_redirect_stage',
            'type_id' => 'startRemoveRedirectStageMigration',
            'success' => $success ?: true,
            'size' => count($migrationRecords),
        );
    }

    /**
     * runMergeMigrationBatch
     *
     * This will run a migration action on the `merge` migration records. It will load a batch of
     * records for the migration and will then determine if we're merging a tag with another tag or if we're merging a
     * tag with a stage. The process is pretty similar for each we just load all the posts that are in the starting tag
     * that aren't also in the ending tag/stage. Then we loop over those posts and add the new tag/stage to the posts.
     * For stages that entails a few more fields to be set such as the age ranges. Once this is completed for all posts
     * we will setup a redirect in Yoast for the target tag page to the new category/stage page and then update that
     * migration record to processed and set its results in the `$batchResults` property.
     *
     */
    public function runMergeMigrationBatch()
    {
        $migrationRecords = $this->getMigrationRecordsByType('merge');
        foreach ($migrationRecords as $migrationRecord) {
            $tagFrom = get_tag($migrationRecord->term_taxonomy_id);
            if ($migrationRecord->target_taxonomy === 'post_tag') {
                $tagTo = get_term_by('slug', $migrationRecord->target_name, 'post_tag');
                $postsToMerge = $this->getPostsFromTagForMerge($migrationRecord->term_taxonomy_id, $migrationRecord->target_name);
                foreach ($postsToMerge as $postToMerge) {
                    wp_set_post_tags($postToMerge->ID, $migrationRecord->target_name, true);
                }
                $this->removeAndRedirectTag($tagFrom, $tagTo);
            } else {
                $stageTo = get_term_by('slug', $migrationRecord->target_name, 'stages');
                $ageFrom = (int)get_field('age_range_from', 'stages_' . $stageTo->term_id);
                $ageTo = (int)get_field('age_range_to', 'stages_' . $stageTo->term_id);
                $postsToMerge = $this->getPostsFromTagAndStageForMerge($tagFrom->term_id, $migrationRecord->target_name);
                foreach ($postsToMerge as $postToMerge) {
                    $this->addStageToPostAndUpdate($postToMerge, $stageTo, $ageFrom, $ageTo);
                }
                $this->removeAndRedirectTag($tagFrom, $stageTo);

            }
            $update = $this->setRecordToProcessed($migrationRecord->id);
            if ($update === false) {
                $success = false;
                break;
            }

        }
        $this->batchResults = array(
            'type_dom' => 'merge',
            'type_id' => 'startMergeMigration',
            'success' => $success ?: true,
            'size' => count($migrationRecords),
        );
    }

    /**
     * getMigrationRecordsByType
     *
     * This will load a batch of records from the migration table of the type passed.
     *
     * @param $type migration record type
     * @return array
     */
    private function getMigrationRecordsByType($type)
    {
        global $wpdb;
        $query = "
                SELECT * FROM $this->tableName
                 WHERE action = '$type'
                 AND processed = 0
                 LIMIT $this->batchSize
        ";
        return $wpdb->get_results($query, OBJECT_K);
    }

    /**
     * removeTagByID
     *
     * This will remove a tag from the site by it's ID.
     *
     * @param $tagID
     * @return bool|int|WP_Error
     */
    private function removeTagByID($tagID)
    {
        return wp_delete_term($tagID, 'post_tag');
    }

    /**
     * setRecordToProcessed
     *
     * This will set the processed column for a migration record to `1` indicating that the record has been processed.
     *
     * @param $recordID
     * @return false|int
     */
    private function setRecordToProcessed($recordID)
    {
        global $wpdb;
        return $wpdb->update($this->tableName, array('processed' => 1), array('id' => $recordID), array('%d'), array('%d'));
    }

    /**
     * getPostsFromTagForMerge
     *
     * This will load all the posts for a merge from a tag to another tag. It will retrieve all the posts that have the
     * `$tagFrom` set but not the `$tagTo` set.
     *
     * @param $tagFromID
     * @param $tagToSlug
     * @return array
     */
    private function getPostsFromTagForMerge($tagFromID, $tagToSlug)
    {
        $args = array(
            'posts_per_page' => -1,
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'post_tag',
                    'field' => 'term_id',
                    'terms' => array($tagFromID)

                ),
                array(
                    'taxonomy' => 'post_tag',
                    'field' => 'slug',
                    'terms' => array($tagToSlug),
                    'operator' => 'NOT IN'
                ),
            )
        );
        $query = new WP_Query($args);
        return $query->posts;
    }

    /**
     * getPostsFromTagAndStageForMerge
     *
     * This will load all the posts for a merge from a tag to a stage. It will retrieve all the posts that have the
     * `$tagFrom` set but not the `$stageTo` set.
     *
     * @param $tagFromID
     * @param $stageToSlug
     * @return array
     */
    private function getPostsFromTagAndStageForMerge($tagFromID, $stageToSlug)
    {
        $args = array(
            'posts_per_page' => -1,
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'post_tag',
                    'field' => 'term_id',
                    'terms' => array($tagFromID)

                ),
                array(
                    'taxonomy' => 'stages',
                    'field' => 'slug',
                    'terms' => array($stageToSlug),
                    'operator' => 'NOT IN'
                ),
            )
        );
        $query = new WP_Query($args);
        return $query->posts;
    }

    /**
     * getPostsFromTagAndCategoryForMerge
     *
     * This will load all the posts for a merge from a tag to a category. It will retrieve all the posts that have the
     * `$tagFrom` set but not the `$categoryTo` set.
     *
     * @param $tagFromID
     * @param $categoryToSlug
     * @return array
     */
    private function getPostsFromTagAndCategoryForMerge($tagFromID, $categoryToSlug)
    {
        $args = array(
            'posts_per_page' => -1,
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'post_tag',
                    'field' => 'term_id',
                    'terms' => array($tagFromID)

                ),
                array(
                    'taxonomy' => 'category',
                    'field' => 'slug',
                    'terms' => array($categoryToSlug),
                    'operator' => 'NOT IN'
                ),
            )
        );
        $query = new WP_Query($args);
        return $query->posts;
    }

    /**
     * getPostsFromTag
     *
     * Will load all the posts that contain a tag.
     *
     * @param $tagID
     * @return array
     */
    private function getPostsFromTag($tagID)
    {
        $args = array(
            'posts_per_page' => -1,
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'post_tag',
                    'field' => 'term_id',
                    'terms' => array($tagID)

                )
            )
        );
        $query = new WP_Query($args);
        return $query->posts;
    }

    /**
     * removeAndRedirectTag
     *
     * Will remove and redirect a tag to another term page. You need to pass the tag to remove and redirect as
     * `$tagFrom` and then the object for the term you want to redirect to as `$termTo`. This method will then use the
     * classes from the Yoast plugin to add a redirect for that tag to the new location and will then delete the tag
     * passed with `$tagFrom`
     *
     * @param $tagFrom |WP_Term
     * @param $termTo |WP_Term
     * @return bool|int|WP_Error
     */
    private function removeAndRedirectTag($tagFrom, $termTo)
    {
        $origin = trim(htmlspecialchars_decode(rawurldecode(wp_make_link_relative(get_term_link($tagFrom, 'post_tag')))));
        $target = trim(htmlspecialchars_decode(rawurldecode(wp_make_link_relative(get_term_link($termTo, $termTo->taxonomy)))));
        $newRedirect = new WPSEO_Redirect($origin, $target, 301, 'plain');
        $option = new WPSEO_Redirect_Option(true);
        $exporters = WPSEO_Redirect_Manager::default_exporters();
        $redirectManager = new WPSEO_Redirect_Manager('plain', $exporters, $option);
        $createRedirect = $redirectManager->create_redirect($newRedirect);
        if ($createRedirect) {
            return wp_delete_term($tagFrom->term_id, 'post_tag');
        }
    }

    /**
     * addStageToPostAndUpdate
     *
     * This will take a post and a stage along with age range information and update a post with that information so
     * that the post will be included in the stage.
     *
     * @param $post
     * @param $stage
     * @param $ageFrom
     * @param $ageTo
     * @return array|false|WP_Error
     */
    private function addStageToPostAndUpdate($post, $stage, $ageFrom, $ageTo)
    {
        $customFields = get_fields($post->ID);
        if (!array_key_exists('age_range_from', $customFields) && !array_key_exists('not_applicable', $customFields)) {
            update_field('age_range_from', $ageFrom, $post->ID);
            update_field('age_range_to', $ageTo, $post->ID);
        }
        return wp_set_post_terms($post->ID, $stage->slug, 'stages', true);
    }
}