<?php require_once 'includes/header.php'; ?>

    <div class="page-content inset">
        <div class="row" style="margin-top: -20px;" id="tabledata">
            <a target="_blank" href="../export/csv" class="btn btn-primary" id="csv"><i class="fa fa-file-text-o"></i> Export CSV</a>
            <a target="_blank" href="../export/excel" class="btn btn-primary" id="excel"><i class="fa fa-file-excel-o"></i> Export Excel</a>
            <div class="clearfix">&nbsp;</div>

            <?php echo $table_data; ?>
        </div>
    </div>

    <hr/>
    <h3>Query Log</h3>
    <div class="footer">
        <?php echo $query; ?>
    </div>
    <span class="alert-warning timetaken">Time Taken: <strong><?php echo $timetaken ? $timetaken : '0.00'; ?></strong> second(s)!</span>
    <br/>
    <br/>
    <div id="printArray">
        <?php echo $printArray; ?>
    </div>

    <script>
        var __table = '<?php echo Flight::get('lastSegment');?>';
    </script>

<?php require_once 'includes/footer.php'; ?>