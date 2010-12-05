function validate()
{
    var total_checked = $('td:eq(0) input[type="checkbox"]:checked', settings.oApi._fnGetTrNodes(settings)).length;
    var visible_checked = $('#data_table tbody input[type="checkbox"]:checked').length;

    notification('#validation_notice', 'close', '', 'attention');

    if(total_checked != visible_checked)
    {
        var total_item = (total_checked == 1) ? 'item' : 'items';
        var visible_item = (visible_checked == 0) ? 'none' : 'only '+visible_checked;
        var per_page = '-1';

        notification('#validation_notice', 'open', 'You have checked '+total_checked+' '+total_item+', but '+visible_item+' were visible. Only visibly checked items can have actions applied to them.  We have updated the table below to show you all checked items.  Please review these selections and resubmit when ready.', 'attention');

        switch(true)
        {
            case (total_checked <= 10):
                per_page = '10';
                break;

            case (total_checked <= 25):
                per_page = '25';
                break;

            case (total_checked <= 50):
                per_page = '50';
                break;

            case (total_checked <= 100):
                per_page = '100';
                break;

            case (total_checked > 100):
                per_page = '-1';
                break;
        }

        oTable.fnSort([[0, 'asc']]);
        $('#data_table_length_select').val(per_page).trigger('change');

        return false;
    }
    if(total_checked == 0 && visible_checked == 0)
    {
        notification('#validation_notice', 'open', 'You have not selected any items.', 'attention');
        return false;
    }
    if($('#action').val() == '')
    {
        notification('#validation_notice', 'open', 'You have not selected an action.', 'attention');
        $('#action').focus();
        return false;
    }
    if($('#action').val() == 'delete')
    {
        if( !confirm('Are you sure you want to delete the checked items?  This cannot be undone.'))
        {
            return false;
        }
    }

    return true;
}