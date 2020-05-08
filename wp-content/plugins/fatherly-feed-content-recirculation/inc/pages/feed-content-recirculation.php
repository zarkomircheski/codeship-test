<div class="wrap">
    <div id="icon-tools" class="icon32"></div>
    <h1>Fatherly Feed Content Recirculation
        <button id="show_add_form" class="button-primary">Add New</button>
    </h1>
    <hr class="wp-header-end">
    <div class="wrap">
        <div id="add-new-form" class="add-new-article-form hidden">
            <h3>Please enter the post id for the post you would like to add to the recirculation below and then click
                "add"</h3>
            <form name="add_post_form" action="#">
                <label for="post_id">Post Id</label>
                <input name="post_id" id="post_id" type="number" required>
                <input class="button-primary" value="add" type="submit" name="add_new_post" id="add_new_post">
                <div id="add_form_spinner" class="hidden">
                    <div class="spinner"></div>
                </div>
            </form>
        </div>
        <table class="wp-list-table widefat fixed striped">
            <thead>
            <tr>
                <th class="column-title">Article URL</th>
                <th class="column-title">Article ID</th>
                <th class="column-title">Status</th>
                <th class="column-title">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($pageData as $article): ?>
                <tr id="<?php echo $article->post_id; ?>">
                    <td class="article-url"><?php echo sprintf("<a href='%s'>%s</a>", $article->post_url,
                            $article->post_url); ?></td>
                    <td class="article-id">
                        <pre><?php echo $article->post_id ?></pre>
                    </td>
                    <td class="article-processed-status"><?php echo ($article->processed == '0') ? "Has not been recirculated" : "Has been recirculated" ?></td>
                    <td id="actions"><input type="submit" value="Reset Processed Status"
                                            class="resetProcessedStatus button-primary"/>
                        <input type="submit" class="deleteFromRecirculation" id="deleteButton" value="Delete from List"
                               class="button-secondary"/></td>
                </tr>
            <?php endforeach; ?>


            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">

</script>