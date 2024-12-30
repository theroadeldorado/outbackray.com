(function() {
    tinymce.PluginManager.add( 'columns', function( editor, url ) {
      // Add Button to Visual Editor Toolbar
      editor.addButton('TwoColumns', {
        title: 'Insert 2 Columns',
        cmd: 'two-columns',
        image: url + '/two-columns.png',
      });

      editor.addCommand('two-columns', function() {
        var selected_text = editor.selection.getContent({
          'format': 'html'
        });

        if ( selected_text.length === 0 ) {
          alert( 'Please select some text.' );
          return;
        }

        var selectedNode = editor.selection.getNode();

        if (selectedNode.classList.contains('columns-2')) {
          return selected_text;
        }

        var open_column = '<div data-columns class="columns-2">';
        var close_column = '</div>';
        var return_text = '';
        return_text = open_column + selected_text + close_column;
        editor.execCommand('mceReplaceContent', false, return_text);
        return;
      });

      editor.addButton('ThreeColumns', {
        title: 'Insert 3 Columns',
        cmd: 'three-columns',
        image: url + '/three-columns.png',
      });

      editor.addCommand('three-columns', function () {
        var selected_text = editor.selection.getContent({
          'format': 'html'
        });

        if ( selected_text.length === 0 ) {
          alert( 'Please select some text.' );
          return;
        }

        var selectedNode = editor.selection.getNode();

        if (selectedNode.classList.contains('columns-3')) {
          return selected_text;
        }

        var open_column = '<div data-columns class="columns-3">';
        var close_column = '</div>';
        var return_text = '';
        return_text = open_column + selected_text + close_column;
        editor.execCommand('mceReplaceContent', false, return_text);
        return;
      });

    });
})();
