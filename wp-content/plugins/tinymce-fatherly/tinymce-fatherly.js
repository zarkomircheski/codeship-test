(function() {
  tinymce.PluginManager.add('fatherly', function(editor, url) {
    editor.addButton('pull_quote', {
      title: 'Insert pull quote',
      icon: 'blockquote',
      onclick: function() {
        tinymce.activeEditor.formatter.toggle('pullQuote');
      }
    });

    editor.addButton('end_block', {
      title: 'Insert end block',
      onclick: function() {
        tinymce.activeEditor.execCommand('mceInsertContent', false, '<span class="end-square">&nbsp;</span>');
      }
    });

    editor.addButton('bg_highlight', {
      title: 'Toggle highlight',
      icon: 'backcolor',
      onclick: function() {
        var el = editor.selection.getNode();

        if (el.classList.contains('fth-highlight')) {
          editor.dom.removeAllAttribs(el);
        } else  {
          tinymce.activeEditor.formatter.apply('HiliteColor');
        }
      }
    });

    editor.addButton('tips', {
      title: 'Tips Module',
      icon: 'bullist',
      onclick: function() {
        var el = editor.selection.getNode();

        if (el.classList.contains('article__tips')) {
          editor.dom.removeAllAttribs(el);
        } else {
          var text = editor.selection.getContent({format: 'html'});
          
          if (text && text.length > 0) {
            editor.execCommand('mceInsertContent', false, '<div class="article__tips">' + text + '</div>');
          }
        }
      }
    });

    editor.on('init', function() {
      this.formatter.register('pullQuote', {
        inline: 'span', 
        classes: 'pull-quote'
      });

      this.formatter.register('HiliteColor', {
        inline: 'span', 
        classes: 'fth-highlight', 
        styles: {
          background: 'linear-gradient(-180deg, rgba(255, 255, 255, 0), yellow 40%, rgba(255,255,255,0)'
        }
      });
    });
  });
})();
