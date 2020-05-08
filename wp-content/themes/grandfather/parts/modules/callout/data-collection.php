<?php


if (get_query_var('module') !== '') {
    $dataCollection = get_query_var('module')->data;
    $submitClass = get_query_var('class');
} else {
    $dataCollection = $module->data;
}
$count = 0;
$length = is_array($dataCollection['survey_question']) ? count($dataCollection['survey_question']) : 0;
$show = ' show';
?>
<div class="data-collection <?php echo $dataCollection['style_format'].( get_query_var('show') !== null ? ' '.get_query_var('show') : ''); ?>"
 data-id="<?php echo $module->id; ?>">
    <div class="data-collection-cta">
        <div class="data-collection-cta-<?php echo $dataCollection['lead_type']; ?>">
            <?php
            if ($dataCollection['lead_type'] === 'text_lead') {
                echo $dataCollection['title'];
            } else {
                echo '<img src="'.$dataCollection['image'].'" alt="Fatherly IQ">';
            }
            ?>
        </div>
        <?php if ($dataCollection['style_format'] === 'registry') { ?>
            <div class="data-collection-cta-counter">
                <span data-length="<?php echo $length ?>">1</span> / <?php echo $length; ?>
            </div>
        <?php } ?>
    </div>
        <div class="data-collection-info">
            <?php if ($dataCollection['ask_age']) { ?>
                <div class="data-collection-info-title">
                    Your child's birthday or due date
                </div>
            <?php } ?>
            <form id="<?php echo $submitClass ?>" class="data-collection-info-form">
                <?php if ($dataCollection['ask_age']) { ?>
                <div class="data-collection-info-form-child child-1">
                    <input type="date" name="dob-1" min="2000-01-01" max="2022-01-01" required>
                    <div class="data-collection-info-form-child-gender">
                        <input type="radio" name="gender-1" value="male" required><span>Girl</span>
                        <input type="radio" name="gender-1" value="female" required><span>Boy</span>
                        <input type="radio" name="gender-1" value="other" required><span>Other</span>
                        <input type="radio" name="gender-1" value="notSure" required><span>Not Sure</span>
                    </div>
                </div>
                <?php } else if ($dataCollection['survey_question']) {
                    echo $dataCollection['style_format'] === 'registry' ? '<div class="data-collection-info-form-slider"><ol>' : '<ol>';
                    $weights = array();
                    foreach ($dataCollection['survey_question'] as $question) {
                        echo '<li id="pane'.$count.'" class="data-collection-info-form-slider-question'.$show.'" data-weights='.json_encode($question['enter_weight']).'>';
                        $show = ''; ?>
                        <div id="question-<?php echo $count ?>" class="data-collection-info-form-survey" data-clicked="false">
                            <div class="data-collection-info-form-survey-question" data-question="<?php echo $question['question']; ?>"><?php echo $question['question']; ?></div>
                            <div class="data-collection-info-form-survey-answers">
                        <?php $countAnswers = 0;
                        foreach ($question['enter_answer'] as $answers) {
                            echo '<div data-answer="'.$answers['answer'].'" class="data-collection-info-form-survey-answer '.($countAnswers === 0 ? 'right">' : 'left">').$answers['answer'].'</div>';
                            $countAnswers++;
                        }
                        echo '</div></div><div class="like"></div><div class="dislike"></div></li>';
                        $count++;
                    }

                    if ($dataCollection['style_format'] === 'registry') {
                        echo '<li id="pane' . $count . '" class="data-collection-info-form-survey-question-success success no-reorder no-swipe">Creating your registry...</li></ol></div>'; ?>
                        <div class="data-collection-swipe">select your answer above</div>
                    <?php } else {
                        echo '<ol>';
                    }
                } ?>
            </form>
            <?php if ($dataCollection['ask_age']) { ?>
                <div class="data-collection-info-divider"></div>
                <div class="data-collection-info-buttons">
                    <div class="data-collection-info-buttons-add"> Add A Child</div>
                    <div class="data-collection-info-buttons-remove"> Remove A Child</div>
                </div>
                <div class="data-collection-info-divider"></div>
                <input type="submit" form="<?php echo get_query_var('class'); ?>" class="button data-collection-info-save" value="SAVE">
                <div class="data-collection-info-other">I don't have kids</div>
            <?php } ?>
        </div>
    <div class="data-collection-success">
        <?php
        if ($dataCollection['lead_type'] === 'text_lead') {
            echo 'Thanks For Subscribing!';
        } else {
            echo 'Thanks for the feedback!';
        }
        ?>
    </div>
    <div class="data-collection-error">
        Oops! Something went wrong. Please contact <a href="mailto:support@fatherly.com">support@fatherly.com</a>.
    </div>
</div>
<div class="data-collection-background"></div>