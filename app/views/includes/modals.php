<!-- delete confirm modal start -->
<div class="modal fade" id="modal-delete-confirm">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header label-danger">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-check-square-o"></i> <span
                       class="text-white bold">Delete</span></h4>
            </div>
            <div class="modal-body">
                <p class="pull-left" style="margin-right: 10px;"><i
                       class="glyphicon-4x glyphicon glyphicon-question-sign"></i></p>

                <p>You are about to delete, this procedure is irreversible.</p>

                <p>Do you want to proceed?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>
                    Close
                </button>
                <button type="button" class="btn btnDelete btn-danger"><i class="fa fa-trash-o"></i> Delete</button>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- delete confirm modal end -->

<div class="modal fade" id="modal-detail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header label-success">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="glyphicon glyphicon-info-sign"></i> <span
                       class="text-white bold">Todo Details</span></h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal"><i class="fa fa-times"></i>
                    Close
                </button>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-custom-query">
    <div class="modal-dialog">
        <form action="" method="post">

            <div class="modal-content">
                <div class="modal-header label-success">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="text-white fa fa-pencil-square-o"></i> <span
                           class="text-white bold">Custom Query</span></h4>
                </div>

                <div class="modal-body">
                    <div id="ace"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="btnCustomQuery" class="btn btn-success"><i class="fa fa-play"></i>
                        Run Query
                    </button>

                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>
                        Close
                    </button>
                </div>

            </div>

            <input type="hidden" id="cquery" name="cquery"/>

        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-visual-query">
    <div class="modal-dialog">
        <form action="" method="post" class="form-horizontal" role="form">

            <div class="modal-content">
                <div class="modal-header label-success">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="text-white fa fa-database"></i> <span
                           class="text-white bold">Visual Query (<?php echo Flight::get('lastSegment'); ?>)</span></h4>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <button style="margin-bottom: 10px !important;" type="button" id="btnJoinTable" class="btn btn-primary" rel="hover_popover" data-content="Join a table">
                            <i class="glyphicon glyphicon-plus-sign"></i> Join Table
                        </button>
                        <br/>
                        <a style="display: none;" href="#" id="addjoinedtablefields"><i class="fa fa-refresh"></i> Click
                            to add Joined Table Fields</a>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="fields">Select Fields</label>

                        <div class="controls">
                            <select name="fields[]" id="fields" multiple class="fields form-control col-lg-8">
                                <?php echo $fields; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <button style="margin-bottom: 10px !important;" type="button" id="btnAddWhere" class="btn btn-primary" rel="hover_popover" data-content="Add WHERE clause conditions">
                            <i class="glyphicon glyphicon-plus-sign"></i> Add Condition
                        </button>
                    </div>

                    <div class="form-group">
                        <button type="button" id="btnOrderby" class="btn btn-primary" rel="hover_popover" data-content="Add ORDER BY clause fields">
                            <i class="glyphicon glyphicon-plus-sign"></i> Add Order
                        </button>
                    </div>

                    <div class="form-group parent" style="display: none;" id="orderby">
                        <div class="pull-left">
                            <a href="#" class="remove"><i class="glyphicon glyphicon-trash glyphicon-2x" style="margin-top: 5px;"></i></a>
                        </div>
                        <div class="pull-left" style="margin: 3px;">
                            &nbsp;
                        </div>
                        <div class="controls pull-left">
                            <select name="orderfields[]" id="orderfields" multiple class="orderfields form-control" style="width: 400px;">
                                <?php echo $fields; ?>
                            </select>
                        </div>
                        <div class="pull-left">
                            &nbsp;&nbsp;
                        </div>
                        <div class="controls pull-left">
                            <input type="checkbox" id="chkDescending" name="chkDescending"/>
                            <label class="control-label" for="chkDescending" class="form-control">Descending</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="button" id="btnGroup" class="btn btn-primary" rel="hover_popover" data-content="Add GROUP BY clause fields">
                            <i class="glyphicon glyphicon-plus-sign"></i> Add Group Field
                        </button>
                    </div>

                    <div class="form-group parent" style="display: none;" id="group">
                        <div class="pull-left">
                            <a href="#" class="remove"><i class="glyphicon glyphicon-trash glyphicon-2x" style="margin-top: 5px;"></i></a>
                        </div>
                        <div class="pull-left" style="margin: 3px;">
                            &nbsp;
                        </div>
                        <div class="controls pull-left">
                            <select name="groupfields[]" id="groupfields" multiple class="groupfields form-control" style="width: 400px;">
                                <?php echo $fields; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="button" id="btnLimit" class="btn btn-primary" rel="hover_popover" data-content="Add LIMIT clause details">
                            <i class="glyphicon glyphicon-plus-sign"></i> Add Limit
                        </button>
                    </div>

                    <div class="form-group parent" style="display: none;" id="limit">
                        <div class="pull-left">
                            <a href="#" class="remove"><i class="glyphicon glyphicon-trash glyphicon-2x" style="margin-top: 5px;"></i></a>
                        </div>
                        <div class="pull-left" style="margin: 3px;">
                            &nbsp;
                        </div>
                        <div class="controls pull-left">
                            <input type="number" id="limitStart" name="limitStart" class="form-control" placeholder="Starting Row ID"/>
                        </div>
                        <div class="pull-left">
                            &nbsp;&nbsp;
                        </div>
                        <div class="controls pull-left">
                            <input type="number" id="limitNumRows" name="limitNumRows" class="form-control" placeholder="Number of Rows"/>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <input type="checkbox" id="printArray" name="printArray"/>
                    <label for="printArray">Print POST Array</label>
                    &nbsp;&nbsp;

                    <button type="submit" id="btnVisualQuery" class="btn btn-success"><i class="fa fa-play"></i>
                        Run Query
                    </button>

                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>
                        Close
                    </button>
                </div>

            </div>

            <input type="hidden" name="vquery"/>

        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="fieldClone" class="parent" style="display: none; margin: 3px;">
    <div class="pull-left">
        <a href="#" class="remove"><i class="glyphicon glyphicon-trash glyphicon-2x" style="margin-top: 5px;"></i></a>
    </div>
    <div class="pull-left" style="margin: 3px;">
        &nbsp;
    </div>
    <div class="pull-left">
        <select name="ftype[]" class="form-control" style="width: 70px;">
            <option value="AND">AND</option>
            <option value="OR">OR</option>
        </select>
    </div>
    <div class="pull-left" style="margin: 3px;">
        &nbsp;
    </div>
    <div class="pull-left">
        <select name="fname[]" placeholder="Field Name" class="fname form-control" style="width: 250px;">
            <?php echo $fields; ?>
        </select>
    </div>
    <div class="pull-left" style="margin: 3px;">
        &nbsp;
    </div>
    <div class="pull-left">
        <input type="text" name="fvalue[]" placeholder="Operator + Value eg = 5 or != 10" class="form-control" style="height: 28px; width: 250px;">
    </div>
    <div class="clearfix"></div>
</div>

<div id="fieldCloneTable" class="parent" style="display: none; margin: 3px;">
    <div class="pull-left">
        <a href="#" class="remove removeme"><i class="glyphicon glyphicon-trash glyphicon-2x" style="margin-top: 5px;"></i></a>
    </div>
    <div class="pull-left" style="margin: 3px;">
        &nbsp;
    </div>
    <div class="pull-left">
        <select name="jointype[]" class="form-control" style="width: 150px;">
            <option value="INNER JOIN">INNER JOIN</option>
            <option value="LEFT JOIN">LEFT JOIN</option>
            <option value="RIGHT JOIN">RIGHT JOIN</option>
            <option value="FULL JOIN">FULL JOIN</option>
        </select>
    </div>
    <div class="pull-left" style="margin: 3px;">
        &nbsp;
    </div>
    <div class="pull-left">
        <select name="jointable[]" class="jointable form-control" style="width: 160px;">
            <option value="">Joining Table</option>
            <?php echo Flight::get('tablesOptions'); ?>
        </select>
    </div>
    <div class="pull-left" style="margin: 3px;">
        &nbsp;
    </div>
    <div class="pull-left">
        <select name="joinfield[]" class="joinfieldselected form-control" style="width: 160px;">
            <option value="">Joining Field</option>
        </select>
    </div>
    <div class="pull-left" style="margin: 3px;">
        &nbsp;
    </div>
    <div class="pull-left">
        <select name="joinfieldp[]" class="joinfieldmain form-control" style="width: 160px;">
            <option value="">Joining Field Main</option>
            <?php echo $fields; ?>
        </select>
    </div>
    <div class="clearfix"></div>
</div>
