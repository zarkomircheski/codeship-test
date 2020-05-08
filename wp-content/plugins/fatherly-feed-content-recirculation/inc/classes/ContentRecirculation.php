<?php

namespace FCR;

/**
 * Class ContentRecirculation
 * @package FCR
 */
class ContentRecirculation
{
    /**
     * $tableName
     *
     * The name of the table holding the posts for recirculation
     *
     * @var string
     */
    private $tableName;

    /**
     * ContentRecirculation constructor.
     */
    public function __construct()
    {
        global $wpdb;
        $this->tableName = $wpdb->prefix . "feed_content_recirculation_posts";
    }

    /**
     * init
     * @return ContentRecirculation
     */
    public static function init()
    {
        return new self;
    }

    /**
     * getPostsForRecirculation
     *
     * Fetches 10 posts that have not been recirculated from our table and then loads their post objects as well as
     * updates the rows to show that they have been shown
     *
     * @return array
     */
    public function getPostsForRecirculation()
    {
        global $wpdb;
        $query = "
                SELECT * FROM $this->tableName
                 WHERE processed = 0
                 ORDER BY RAND()
                 LIMIT 10
        ";
        $queryResults = $wpdb->get_results($query, OBJECT_K);
        if (count($queryResults) < 10) {
            $this->resetAllPostsToUnprocessed();
            return array();
        } else {
            $posts = array();
            foreach ($queryResults as $result) {
                $posts[] = get_post($result->post_id);
                $this->updateProcessedStatusOfArticle($result->id);
            }
            return $posts;
        }
    }

    /**
     * resetAllPostsToUnprocessed
     *
     * Once all posts have been recirculated this method will reset them all so that they can be recirculated once more
     *
     * @return false|int
     */
    public function resetAllPostsToUnprocessed()
    {
        global $wpdb;
        return $wpdb->query(sprintf("UPDATE %s SET processed=0",$this->tableName));
    }


    /**
     * updateProcessedStatusOfArticle
     *
     * This method will update the status of an article to show that it has been recirculated
     *
     * @param $recordID
     * @return false|int
     */
    public function updateProcessedStatusOfArticle($recordID)
    {
        global $wpdb;
        return $wpdb->update($this->tableName, array('processed' => 1), array('id' => $recordID), array('%d'), array('%d'));
    }


    /**
     * getAdminPageData
     *
     * Fetches all the rows from our table for use on the admin settings page
     *
     * @return array|null|object
     */
    public function getAdminPageData()
    {
        global $wpdb;
        $query = "
                SELECT * FROM $this->tableName 
                ORDER BY processed DESC
        ";
        $queryResults = $wpdb->get_results($query, OBJECT_K);
        return $queryResults;
    }


    /**
     * addNewPostToRecirculation
     *
     * Responsible for adding new posts to the recirculation table.
     *
     * @param $post_id
     * @return array
     */
    public function addNewPostToRecirculation($post_id)
    {
        global $wpdb;
        $post_permalink = get_post_meta($post_id, 'post_permalink', true) ?: get_permalink($post_id);
        $recordExist = $this->getRecordByPostID($post_id);
        if ($post_permalink && !$recordExist) {
            $columnData = array(
                'post_id' => $post_id,
                'post_url' => $post_permalink
            );
            $insert = $wpdb->insert($this->tableName, $columnData, array('%d', '%s'));
            if ($insert !== false) {
                return $this->sendSuccessResponse(__('Post Added Succesfully'), $columnData);
            } else {
                $message = sprintf("Insertion failed for this item please report this to #site-build with the post id %d", $post_id);
                return $this->sendErrorResponse(__($message));
            }
        } else {
            return $this->sendErrorResponse(__(sprintf("A post with the ID %d already exists in this table", $post_id)));
        }
    }

    /**
     * getRecordByPostID
     *
     * Fetches a recirculation record from our table by the post id of that record
     *
     * @param $post_id
     * @return array|null|object|void
     */
    public function getRecordByPostID($post_id)
    {
        global $wpdb;
        return $wpdb->get_row("SELECT * FROM $this->tableName WHERE post_id = $post_id");
    }


    /**
     * sendSuccessResponse
     *
     * When an action completes succesfully on the admin page this method will return that message to the front end
     *
     * @param $message
     * @param $object
     * @return array
     */
    protected function sendSuccessResponse($message, $object)
    {
        return array(
            'success' => true,
            'message' => $message,
            'object' => $object
        );
    }


    /**
     * sendErrorResponse
     *
     * When an action is unable to complete on the admin page this method will return that message to the front end
     *
     * @param $message
     * @return array
     */
    protected function sendErrorResponse($message)
    {
        return array(
            'success' => false,
            'message' => $message
        );
    }


    /**
     * deletePostFromRecirculation
     *
     * This method will delete a post from recirculation if an admin chooses to do so
     *
     * @param $post_id
     * @return array
     */
    public function deletePostFromRecirculation($post_id)
    {
        global $wpdb;
        $delete = $wpdb->delete($this->tableName, array('post_id' => $post_id));
        if ($delete !== false) {
            $message = sprintf("Post with the ID %d has been deleted", $post_id);
            return $this->sendSuccessResponse($message, $post_id);
        } else {
            $message = sprintf("Post with ID %d could not be deleted", $post_id);
            return $this->sendErrorResponse($message);
        }
    }


    /**
     * resetPostProcessedStatus
     *
     * This will toggle the recirculation status of a post by its post id. Is used on the admin page to allow admins to
     * easily change the status of individual records at will.
     *
     * @param $post_id
     * @return array
     */
    public function resetPostProcessedStatus($post_id)
    {
        global $wpdb;
        $rowCurrent = $this->getRecordByPostID($post_id);
        $processedUpdatedValue = ($rowCurrent->processed == 0) ? 1 : 0;
        $updateData = array(
            'processed' => $processedUpdatedValue
        );
        $updateWhere = array('post_id' => $post_id);

        $update = $wpdb->update($this->tableName, $updateData, $updateWhere, array('%d'), array('%d'));
        if ($update !== false) {
            $rowCurrent->processed = $processedUpdatedValue;
            $message = sprintf("Post with ID %d has had its processed status reset", $post_id);
            return $this->sendSuccessResponse($message, $rowCurrent);
        } else {
            $message = sprintf("Post with the ID %d could not be updated", $post_id);
            return $this->sendErrorResponse($message);
        }
    }
}
