<h2><img src="<?PHP echo $media_url;?>system/images/icons/24x24/save.png" alt="icon" style="vertical-align: middle; margin-top: -4px;" /> <?PHP echo $page_title; ?></h2>

<p id="page-intro"><?PHP echo $file_name; ?></p>

<p><a class="button" href="<?PHP echo $back_button; ?>">&laquo; Back</a> <a class="button" href="#" onclick="location.reload(); return false;">Refresh</a></p>
<br />

<div class="content-box">
    <div class="content-box-header">
        <h3><?PHP echo $file_details['name'].' <span style="color: #999; font-weight: normal;">&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp; '.$file_details['modified'].'</span>'; ?></h3>
        <div class="clear"></div>
    </div>

    <div class="content-box-content">
        <div class="tab-content default-tab" id="tab1">
            <pre class="brush: <?PHP echo $file_type; ?>;  toolbar: false; smart-tabs: true;"><?PHP echo htmlentities($file_content); ?></pre>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?PHP echo $media_url;?>system/js/sh/shCore.js"></script>
<script type="text/javascript" src="<?PHP echo $media_url;?>system/js/sh/shBrush<?PHP echo ucfirst($file_type); ?>.js"></script>
<script type="text/javascript">
	var s = document.createElement('link');
	s.setAttribute('href','<?PHP echo $media_url;?>system/css/sh/shCoreDefault.css');
	s.setAttribute('rel','stylesheet');
	s.setAttribute('type','text/css');
	document.getElementsByTagName('head')[0].appendChild(s);
	
	SyntaxHighlighter.all();
</script>