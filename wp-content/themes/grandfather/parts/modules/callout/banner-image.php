<?php
if (isset($module->data['image_link']) && is_array($module->data['image_link'])) {
    $content = sprintf('<a target="_blank" href="%s"><img class="top-image"src="%s"></a>', $module->data['image_link']['url'], $module->data['image']);
} else {
    $content = sprintf('<img class="top-image" src="%s">', $module->data['image']);
}
echo $content;
