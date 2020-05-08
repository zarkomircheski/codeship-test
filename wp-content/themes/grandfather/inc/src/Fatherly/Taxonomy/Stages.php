<?php

namespace Fatherly\Taxonomy;

class Stages
{
    public $stagesAgeRanges;
    public $currentStages;
    public $currentStagesSlugs;
    public $updatedStages;
    public $updatedStagesSlugs;

    public static function init()
    {
        return new self;
    }

    public function __construct()
    {
        $this->stagesAgeRanges = $this->generateStagesArray();
    }

    protected function generateStagesArray()
    {
        $data = array();
        $stages = get_terms(array(
            'taxonomy' => 'stages',
        ));
        foreach ($stages as $stage) {
            $data[$stage->slug] = array(
                'start' => (int)get_field('age_range_from', $stage->taxonomy . '_' . $stage->term_id),
                'end' => (int)get_field('age_range_to', $stage->taxonomy . '_' . $stage->term_id)
            );
        }
        return $data;
    }

    public function addStagesToPost($post)
    {
        //If the na field is true then we want to remove any stages that are currently set on the article and then bail
        if (get_field('not_applicable', $post->ID) === true) {
            wp_delete_object_term_relationships($post->ID, 'stages');
            return $this;
        }
        //get current stages
        $this->currentStages = $this->getStagesFromPost($post);
        $this->currentStagesSlugs = wp_list_pluck($this->currentStages, 'slug');
        //determine new stages
        $this->updatedStages = $this->determineStagesFromAgeRange($post);
        sort($this->currentStagesSlugs);
        sort($this->updatedStagesSlugs);
        //apply changes to stages if needed.
        if ($this->currentStagesSlugs != $this->updatedStagesSlugs) {
            $ids = wp_list_pluck($this->updatedStages, 'term_id');
            wp_set_post_terms($post->ID, $ids, 'stages');
        }
    }


    public function getStagesFromPost($post)
    {
        return wp_get_post_terms($post->ID, 'stages');
    }

    public function determineStagesFromAgeRange($post)
    {
        $ageRangeStart = intval(get_field('age_range_from', $post->ID));
        $ageRangeEnd = intval(get_field('age_range_to', $post->ID));
        $stages = array();
        $slugs = array();
        foreach ($this->stagesAgeRanges as $stage => $range) {
            if ($ageRangeStart <= $range['start'] && $range['end'] <= $ageRangeEnd) {
                /**
                 * If the age range start is less than or equal to the start for the stage AND
                 * If the end for the stage is less than or equal to the age range end
                 */
                $stages[] = get_term_by('slug', $stage, 'stages');
                $slugs[] = $stage;
            } elseif ($ageRangeEnd > $range['start'] && $ageRangeEnd <= $range['end']) {
                /**
                 * If the age range end is greater than the stage start AND
                 * If the age range end is less than or equal to the stages end
                 */
                $stages[] = get_term_by('slug', $stage, 'stages');
                $slugs[] = $stage;
            } elseif ($ageRangeStart >= $range['start'] && $ageRangeStart <= $range['end']) {
                /**
                 * If the age range start is greater than or equal to the stage start AND
                 * If the age range start is less than  or equal to the stage end
                 */
                $stages[] = get_term_by('slug', $stage, 'stages');
                $slugs[] = $stage;
            }
        }
        $this->updatedStagesSlugs = $slugs;
        return $stages;
    }
}
