/**
 * Created by SARFRAZ on 7/27/14
 */

// select active navigation item
$('.sidebar-nav a').parents('li').removeClass('activelink');
$('.sidebar-nav a[href$="' + lastSegment + '"]').parents('li').addClass('activelink');

// style tables
if ($('table tr').length) {
    $('.page-content table').not('table.nodatatable').dataTable({
        sPaginationType: "full_numbers",
        bAutoWidth: false,
        autoWidth: false,
        bLengthChange: true,
        iDisplayLength: 10,
        scrollX: 400
    });
}

// replace selects with select2
$('select').select2({ placeholder: 'Choose' });

// for tooltips
$(".tip").tooltip();

// inline bootstrap editable
$.fn.editable.defaults.mode = 'popup';

$('a.editable').editable({
    validate: function (value) {
        if ($.trim(value) == '') return 'Cannot be empty!';
    }
});

$('a.editable').editable();

// for popover
$('#modal-visual-query [rel=hover_popover]').popover({ "trigger": "hover", "placement": "right"});
$('[rel=hover_popover]').popover({ "trigger": "hover", "placement": "bottom"});

// run custom query
$('#btnCustomQuery').click(function () {
    var query = editor.getValue();
    var $input = $(this).closest('form').find('#cquery');
    var $form = $(this).closest('form').get(0);

    if (!$.trim(query)) {
        $.jGrowl('Please specify query first!', { sticky: false, header: 'Error' });
        return false;
    }

    $input.val(query);
    $form.submit();
});

// add query condition for visual query
$('#btnAddWhere').click(function () {
    var $clone = $('#fieldClone').clone();
    $(this).after($clone);
    $clone.slideDown('fast');
    //$clone.find('select').select2({});
    $clone.find('.select2-container').remove();
    $clone.find('select').select2();
});

// add order by clause for visual query
$('#btnOrderby').click(function () {
    $('#orderby').slideDown('fast');
});

// add limit clause for visual query
$('#btnLimit').click(function () {
    $('#limit').slideDown('fast');
});

// add group by clause for visual query
$('#btnGroup').click(function () {
    $('#group').slideDown('fast');
});

// remove items for visual query
$('body').on('click', '.remove', function () {
    // update tables fields dropdowns when they are removed
    if ($(this).hasClass('removeme')) {
        $(this).closest('.parent').slideUp('fast').remove();
        addTablesToDropdown();
    }
    else {
        $(this).closest('.parent').slideUp('fast');
    }

    if (!$('.removeme:visible').length) {
        $('#addjoinedtablefields').slideUp('fast');
    }

    return false;
});

// join table for visual query
$('#btnJoinTable').click(function () {
    var $clone = $('#fieldCloneTable').clone();
    $(this).after($clone);
    $clone.slideDown('fast');

    $clone.find('.select2-container').remove();
    $clone.find('.joinfieldselected').empty();
    $clone.find('select').select2();

    $('#addjoinedtablefields').slideDown('fast');
});

// to get fields for selected table for visual query
$('body').on('change', 'select.jointable', function () {
    var value = this.value;

    if (value) {
        var $this = $(this);

        $.post(base + '/ajax/gettablefields', {"table": value}, function (response) {
            var $select = $this.closest('.parent').find('select.joinfieldselected');

            $select.html(response);
            $select.select2();
        });
    }
});

$('#addjoinedtablefields').click(addTablesToDropdown);

// dynamically populate dropdowns for selected tables for visual query
function addTablesToDropdown() {
    var selectedTables = [lastSegment];

    $('.jointable').each(function () {
        var value = $(this).val();

        if (value.length) {
            selectedTables.push(value);
        }
    });

    if (selectedTables.length > 0) {
        $.post(base + '/ajax/getselectfields', {"tables": JSON.stringify(selectedTables)}, function (response) {
            var $fnameDropdown = $('select.fname');
            var $fieldsDropdown = $('select.fields');
            var $orderFieldsDropdown = $('select.orderfields');
            var $groupFieldsDropdown = $('select.groupfields');

            $fnameDropdown.html(response);
            $fieldsDropdown.html(response);
            $orderFieldsDropdown.html(response);
            $groupFieldsDropdown.html(response);

            $fnameDropdown.select2();
            $fieldsDropdown.select2();
            $orderFieldsDropdown.select2();
            $groupFieldsDropdown.select2();

            $.jGrowl('Done updating fields!', { sticky: false, header: 'Status' });
        });
    }
}

// change database
$('#database').change(function () {
    if (this.value) {
        $.post(base + '/ajax/setDatabase', {"db": this.value}, function (response) {
            if (response === 'ok') {
                window.location.href = base;
            }
        });
    }
});
