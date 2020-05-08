<?php
add_action('show_user_profile', 'fth_show_add_child_info');
add_action('edit_user_profile', 'fth_show_add_child_info');
add_action('personal_options_update', 'fth_save_add_child_info');
add_action('edit_user_profile_update', 'fth_save_add_child_info');

function fth_show_add_child_info($user)
{
    $children_meta = get_the_author_meta('children', $user->ID);
    $saved_status = get_the_author_meta('user_saved', $user->ID);
    $children = json_decode($children_meta);
    $dictionary  = array(
        0=> 'one',
        1=> 'two',
        2=> 'three',
    );
    $i = 0; ?>
    <h3 class="user-child-form__title">Personalize</h3>
    <p class="user-child-form__description">Get personalized recommendations based on the age of your kid. Your info is 100% secure and we will never sell or share it.</p>
    <table class="form-table user-child-form clearfix">
            <tr>
                <th><label for="child_<?php echo $dictionary[$i] ?>_birthday">Child's Birthday / Due Date</label></th>
                <td>
                    <input type="text" name="child_<?php echo $dictionary[$i] ?>_birthday" id="child_<?php echo $dictionary[$i] ?>_birthday" data-index="<?php echo $i; ?>" value="<?php echo (isset($children[$i]) && isset($children[$i]->birthday)) ? $children[$i]->birthday  : ''; ?>" class="regular-text" placeholder="mm/dd/yyyy" /><br />
                    <span class="description">Please enter your child's birthday / due date</span>
                    <span class="invalid-bday-notice">Invalid birthday, please write in the proper format: mm/dd/yyyy</span>
                </td>
            </tr>
            </table>

    <input type="hidden" name="user_saved" id="user_saved" value="<?php echo ($saved_status == true) ? 'true' : 'false';?>" />
    <?php
}function fth_save_add_child_info($user_id)
{
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }
    $children = array();
    if (isset($_POST['child_one_birthday'])) {
        $first_child = array(
            'index' => 'one',
            'birthday' => $_POST['child_one_birthday'],
            'trying' => $_POST['child_one_trying']
        );
        array_push($children, $first_child);
    }
    if (isset($_POST['child_two_birthday'])) {
        $second_child = array(
            'index' => 'two',
            'birthday' => $_POST['child_two_birthday'],
            'trying' => $_POST['child_two_trying']
        );
        array_push($children, $second_child);
    }
    if (isset($_POST['child_three_birthday'])) {
        $third_child = array(
            'index' => 'three',
            'birthday' => $_POST['child_three_birthday'],
            'trying' => $_POST['child_three_trying']
        );
        array_push($children, $third_child);
    }
    $z = 0;
    foreach ($children as $child) {
        if ($child['trying'] == '0') {
            update_user_meta(absint($user_id), 'true', json_encode($child));
        }
        if ((!isset($child['birthday']) || $child['birthday'] == '') && (isset($child['trying']) && $child['trying'] == '0')) {
            array_splice($children, $z, 1);
            $z--;
        }
        $z++;
    }
    $json_children = json_encode($children);
    update_user_meta(absint($user_id), 'children', $json_children);
    update_user_meta(absint($user_id), 'user_saved', true);
}
