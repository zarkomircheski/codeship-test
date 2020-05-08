<style>
    body.tools_page_tag-migrator tr.is-error {
        background-color: #FF4c4c;
    }

    body.tools_page_tag-migrator tr.is-error td {
        color: #ffffff;
    }

    body.tools_page_tag-migrator tr.is-complete {
        background-color: #00C975;
    }

    body.tools_page_tag-migrator tr.is-complete td {
        color: #ffffff;
    }
</style>
<div class="wrap">
    <div id="icon-tools" class="icon32"></div>
    <h1>Fatherly Tag Migrator</h1>
    <div class="wrap">
        <table class="wp-list-table widefat fixed striped">
            <thead>
            <tr>
                <th class="column-title">Migration Type</th>
                <th class="column-title">Items to process</th>
                <th class="column-title">Actions</th>
                <th id="completedHeader" class="hidden column-title">Completed <strong>(%)</strong></th>
            </tr>
            </thead>
            <tbody>
            <tr id="remove_only">
                <td>Remove Only</td>
                <td id="total"></td>
                <td><input type="submit" id="startRemoveMigration" value="Run" class="button-primary"/></td>
                <td id="progress" class="hidden"><strong data-processed="0">0</strong></td>
            </tr>
            <tr id="merge">
                <td>Merge</td>
                <td id="total"></td>
                <td><input type="submit" id="startMergeMigration" value="Run" class="button-primary"/></td>
                <td id="progress" class="hidden"><strong data-processed="0">0</strong></td>
            </tr>
            <tr id="remove_and_redirect_stage">
                <td>Remove & Redirect Stage</td>
                <td id="total"></td>
                <td><input type="submit" id="startRemoveRedirectStageMigration" value="Run" class="button-primary"/>
                </td>
                <td id="progress" class="hidden"><strong data-processed="0">0</strong></td>
            </tr>
            <tr id="remove_and_redirect">
                <td>Remove & Redirect</td>
                <td id="total"></td>
                <td><input type="submit" id="startRemoveRedirectMigration" value="Run" class="button-primary"/></td>
                <td id="progress" class="hidden"><strong data-processed="0">0</strong></td>
            </tr>


            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
  jQuery(document).ready(function ($) {
    var tagMigrator = {
      init: function () {
        //Sets up the page with data needed for migration to run correctly.
        this.pageData = <?php echo $pageData; ?>;
        for (var i = 0; i < this.pageData.length; i++) {
          this.applyTotals(this.pageData[i].action, this.pageData[i].total)
        }
      },
      applyTotals: function (type, number) {
        //Applies the totals for how many records need to be processed for each record type to the DOM.
        //If there are no records to process then the run button is disabled.
        $('#' + type + '>td#total').html('<pre>' + number + '</pre>')
        if (number == 0) {
          $('#' + type + '>td>input').attr('disabled', true)
        }
      },
      applyError: function (tableRow) {
        //If an error occurs during the migration then this will inform the end user and highlight the migration that
        //caused the error.
        $(tableRow).addClass('is-error')
        alert('There was a problem with this request. Please report this in #site-build')
      },
      triggerNextBatch: function (eventData) {
        /* Once we have completed a batch of records for a migration this function will trigger the next batch of records
         * for that record to be updated by triggering an event.
         */
        var evt = new CustomEvent('TagMigrationBatch', {
          detail: {
            'type': eventData.type_id,
            'row': eventData.type_dom
          }
        })
        window.dispatchEvent(evt)
      },
      updateProgress: function (returnData) {
        /*
        * When a batch is completed this will update the table with the progress percentage. If the migration is
        * complete then this will turn the row green and disable the run button. If there are still records remaining
        * then this will call the `triggerNextBatch()` function.
         */
        $('#' + returnData.type_dom + '>td#progress').removeClass('hidden')
        total = $('#' + returnData.type_dom + '>td#total>pre').text()
        processedElm = $('#' + returnData.type_dom + '>td#progress>strong')
        processed = (parseInt(processedElm.attr('data-processed')) + returnData.size)
        processedElm.attr('data-processed', processed)
        processedElm.text(Math.ceil(processed / total * 100))
        if (total == processed) {
          $('#' + returnData.type_dom).addClass('is-complete') // add a nice green background to show that it's complete
        } else {
          this.triggerNextBatch(returnData)
        }
      },
      handleRunClick: function (type) {
        /*
        * This is used when someone clicks on the run item for a migration and will get the information needed for the
        * migration and then send off an ajax request to process the records for that migration type.
         */
        self = this
        tableRow = $('#' + type).parent().parent()
        data = {
          'action': 'fatherly_tag_migration',
          'type': type
        }
        $.post(ajaxurl, data, function (res) {
          res = JSON.parse(res)
          if (typeof res.success !== 'undefined' && res.success == true) {
            $('th#completedHeader').removeClass('hidden')
            self.updateProgress(res)
          } else {
            self.applyError(tableRow)
          }
        })
      },
      submitNextBatch: function (type, row) {
        /*
        * When the next batch is triggered for a migration programatically then this is the function that will send the
        * ajax request for the next batch of records to be processed.
         */
        self = this
        tableRow = $('#' + row)
        data = {
          'action': 'fatherly_tag_migration',
          'type': type
        }
        $.post(ajaxurl, data, function (res) {
          res = JSON.parse(res)
          if (typeof res.success !== 'undefined' && res.success == true) {
            $('th#completedHeader').removeClass('hidden')
            self.updateProgress(res)
          } else {
            self.applyError(tableRow)
          }
        })
      }

    }
    tagMigrator.init()

    $('#startMergeMigration,#startRemoveRedirectStageMigration,#startRemoveRedirectMigration,#startRemoveMigration').on('click', function (e) {
      e.preventDefault()
      var runType = $(this).attr('id')
      $(this).attr('disabled', true)//disable the input so we dont trigger it again.
      tagMigrator.handleRunClick(runType)
    })
    window.addEventListener('TagMigrationBatch', function (evt) {
      tagMigrator.submitNextBatch(evt.detail.type, evt.detail.row)
    })
  })
</script>