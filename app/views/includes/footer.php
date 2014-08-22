<br/><br/>

</div>
<!-- End Page content -->
</div>

<?php require_once 'modals.php'; ?>

<!-- JavaScript -->
<script src="<?php echo Flight::get('base'); ?>/assets/js/jquery-1.10.2.js"></script>
<script src="<?php echo Flight::get('base'); ?>/assets/js/bootstrap.min.js"></script>
<script src="<?php echo Flight::get('base'); ?>/assets/plugins/select2/select2.min.js"></script>
<script src="<?php echo Flight::get('base'); ?>/assets/plugins/dataTables/jquery.dataTables.js"></script>
<script src="<?php echo Flight::get('base'); ?>/assets/plugins/dataTables/dataTables.bootstrap.js"></script>
<script
   src="<?php echo Flight::get('base'); ?>/assets/plugins/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script src="<?php echo Flight::get('base'); ?>/assets/plugins/jGrowl/jquery.jgrowl.js"></script>

<script src="<?php echo Flight::get(
   'base'
); ?>/assets/plugins/ace/src-min-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo Flight::get('base'); ?>/assets/plugins/ace/src-min-noconflict/ext-language_tools.js"></script>
<script src="<?php echo Flight::get(
   'base'
); ?>/assets/plugins/ace/src-min-noconflict/mode-mysql.js" type="text/javascript" charset="utf-8"></script>
<script>
    var langTools = ace.require("ace/ext/language_tools");
    var editor = ace.edit("ace");

    editor.setTheme("ace/theme/xcode");
    editor.getSession().setMode("ace/mode/mysql");
    //editor.renderer.setShowGutter(false);

    // enable autocompletion and snippets
    editor.setOptions({
        enableBasicAutocompletion: true,
        enableSnippets: true,
        enableLiveAutocompletion: true
    });

    // add custom list of words for auto-completion
    var tableNames = {
        getCompletions: function (editor, session, pos, prefix, callback) {
            if (prefix.length === 0) {
                callback(null, []);
                return
            }

            $.getJSON(
               "<?php echo Flight::get('base'); ?>/tables.json",
               function (wordList) {
                   // wordList like [{"word":"flow","freq":24,"score":300,"flags":"bc","syllables":"1"}]
                   callback(null, wordList.map(function (res) {
                       return {name: res.word, value: res.word}
                   }));
               })
        }
    }

    langTools.addCompleter(tableNames);

    // set font size
    document.getElementById('ace').style.fontSize = '13px';
</script>

<script>
    var base = "<?php echo Flight::get('base'); ?>";
    var controller = "<?php echo trim(Flight::get('controller'), '/'); ?>";
    var basePath = "<?php echo Flight::get('base'); ?>/";
    var lastSegment = "<?php echo Flight::get('lastSegment'); ?>";
</script>
<script src="<?php echo Flight::get('base'); ?>/assets/js/custom.js?v=<?php echo time(); ?>"></script>

</body>
</html>
