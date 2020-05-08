<div class="wrap">
    <div id="icon-tools" class="icon32"></div>
    <h2>Fatherly Tag Migrator Page</h2>
    <input type="submit" id="startMigration" value="Submit" class="button-primary"/>
    <div class="wrap" id="completed">
    </div>
    <div class="wrap" id="results">
    </div>
</div>
<script type="text/javascript">
  jQuery(document).ready(function ($) {
    var batchEvent = document.createEvent('Event')
    var processed = 0
    batchEvent.initEvent('nextBatch', true, true)
    var terms = <?php echo $terms; ?>;
    console.log(terms)
    var data = {
      'action': 'fth_migrate_posts',
      'term': 'toddlers'
    }
    $('#startMigration').on('click', function (e) {
      e.preventDefault()

      $.post(ajaxurl, data, function (response) {
        var ret = JSON.parse(response)
        total = terms[data.term].count
        processed = processed + ret.count
        $('#results').html('<p> Processed ' + processed + ' posts out of ' + total + ' in ' + data.term + '<strong> ' + parseInt((processed / total) * 100) + '%</strong> </p>')
        document.dispatchEvent(batchEvent)
      })
    })

    $(document).on('nextBatch', function () {
      $.post(ajaxurl, data, function (response) {
        var ret = JSON.parse(response)
        total = terms[data.term].count
        processed = processed + ret.count
        $('#results').html('<p> Processed ' + processed + ' posts out of ' + total + ' in ' + data.term + '<strong> ' + parseInt((processed / total) * 100) + '%</strong> </p>')
        if (typeof(ret.nextTerm) !== 'undefined') {
          $('#completed').append('<p><strong>Completed processing for ' + data.term + ' moved a total of ' + total + ' posts</strong><p>')
          processed = 0
          data.term = ret.nextTerm
        }
        if (ret.more === true) {
          document.dispatchEvent(batchEvent)
        }else{
          $('#completed').append('<p><strong>Completed processing for ' + data.term + ' moved a total of ' + total + ' posts</strong><p>')
        }

      })
    })

  })
</script>
