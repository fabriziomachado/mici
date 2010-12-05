var oTable = null;
var settings = null;

$(document).ready(function(){

    var columns = $('#data_table tbody tr:first td').length;
    
    var aoColumns = [];

    for(var i=0; i<columns; i++)
    {
        if(i==0)
        {
            aoColumns[i] = { 'sSortDataType': 'dom-checkbox', 'bSearchable': false };
        }
        else
        {
            aoColumns[i] = null;
        }
    }

    oTable = $('#data_table').dataTable({
        'bStateSave': true,
        'sPaginationType': 'full_numbers',
        'sDom': 'C<"clear">lifrtp',
        'oColVis': {
            'aiExclude': [ 0, 1 ]
        },
        'bAutoWidth': false,
        'aaSorting': [],
        'aoColumns': aoColumns,
        'fnDrawCallback': highlight()
    });

    settings = oTable.fnSettings();

    new FixedHeader(oTable);
    
    $('.sorting, .sorting_asc, .sorting_desc').dblclick(function(){
        oTable.fnSort([]);
        return false;
    });

    $('.dataTables_filter input').focus();
    $('.sorting, .sorting_asc, .sorting_desc').attr('title', 'Hold Shift and Click to Sort Multiple Columns');
    $('#data_table input[type="checkbox"]').removeAttr('checked');

    $('.check-all').click(
        function(){
            //$('td:eq(0) input[type="checkbox"]', settings.oApi._fnGetTrNodes(settings)).attr('checked', $(this).is(':checked'));
            //$('td:eq(0) input[type="checkbox"]', settings.oApi._fnGetTrNodes(settings)).each(function(){
            $('#data_table tbody input[type="checkbox"]').attr('checked', $(this).is(':checked'));
            $('#data_table tbody input[type="checkbox"]').each(function(){
                if(this.checked)
                {
                    $(this).parents("tr:first").addClass('checked');
                }
                else
                {
                    $(this).parents("tr:first").removeClass('checked');
                }
            });

            var n = $('td:eq(0) input[type="checkbox"]:checked', settings.oApi._fnGetTrNodes(settings)).length;

            if(n > 0)
            {
                $('label.check-all-label').text('select all ( '+n+' selected )');
            }
            else
            {
                $('label.check-all-label').text('select all');
            }
        }
    )

    $.fn.dataTableExt.afnSortData['dom-checkbox'] = function(oSettings, iColumn)
    {
        var aData = [];
        $('td:eq('+iColumn+') input', oSettings.oApi._fnGetTrNodes(oSettings)).each(function(){
            aData.push( this.checked==true ? '0' : '1' );
        });
        return aData;
    }

    function highlight()
    {
        $('#data_table tbody input[type="checkbox"]').change(function(){
            if(this.checked)
            {
                $(this).parents("tr:first").addClass('checked');
            }
            else
            {
                $(this).parents("tr:first").removeClass('checked');
            }

            var n = $('td:eq(0) input[type="checkbox"]:checked', settings.oApi._fnGetTrNodes(settings)).length;

            if(n > 0)
            {
                $('label.check-all-label').text('select all ( '+n+' selected )');
            }
            else
            {
                $('label.check-all-label').text('select all');
            }
        });

        // check if we need to add image previews on pages that support it
        if(typeof imagePreview == 'function')
        {
            imagePreview();
        }
    }
});