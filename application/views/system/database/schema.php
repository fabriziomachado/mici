<h2><img src="<?PHP echo $media_url;?>system/images/icons/24x24/save.png" alt="icon" style="vertical-align: middle; margin-top: -4px;" /> Database Management</h2>

<p id="page-intro">Database Schema ( ORM Designer )</p>

<div class="content-box">
    <div class="content-box-header">
        <h3>Schema</h3>

        <ul class="content-box-tabs">
            <li><a href="#schema" class="default-tab">Schema</a></li>
            <li><a href="#help">Help</a></li>
        </ul>

        <div class="clear"></div>

    </div>

    <div class="content-box-content">
        <div class="tab-content default-tab pancontainer" id="schema">
            <img src="<?PHP echo $media_url;?>system/images/schema.png" />
        </div>

        <div class="tab-content" id="help">
            <p><strong>This Database Schema was developed using ORM Designer.</strong></p>

            <p>You can access the <strong>Doctrine.ormdesigner</strong> file in the <strong>/application/doctrine/orm_designer/</strong> directory.  </p>

            <p>You will need to have <a href="http://www.orm-designer.com/download-orm-designer" target="_blank">ORM Designer</a> installed to open this file.</p>
        </div>
    </div>
</div>

<p><a class="button" href="<?PHP echo $media_url;?>system/images/schema.png" target="_blank">Open Schema in New Window</a></p>

<script type="text/javascript">
var media_url = '<?PHP echo $media_url;?>';
$().ready(function(){
    $('.pancontainer').attr('data-canzoom', 'yes');
    window.onresize = resize_img;
    resize_img();
});

function resize_img()
{
    var height = parseInt($(window).height())-380;
    $('.pancontainer').height(height);
}
</script>
<script type="text/javascript" src="<?PHP echo $media_url;?>system/js/jquery.imagetool.min.js"></script>