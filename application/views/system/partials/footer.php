                <div class="clear"></div>
    
                <div id="footer">
                    <small><a href="http://www.manifestinteractive.com" target="_blank" style="color: #666;" title="Visit Manifest Interactive, LLC"><img src="<?PHP echo $media_url;?>system/images/mi_icon.gif" style="vertical-align: middle;" border="0" alt="Logo" />&nbsp; &#169; Copyright <?PHP echo date('Y'); ?> Manifest Interactive, LLC &nbsp;&middot;&nbsp; All Rights Reserved</a> &nbsp;|&nbsp; <a href="#">Top</a></small>
                </div>
            </div>
		</div>
        
        <script type="text/javascript" src="<?PHP echo $media_url;?>system/js/simpla.jquery.configuration.js"></script>
        <script type="text/javascript" src="<?PHP echo $media_url;?>system/js/facebox.js"></script>
        <script type="text/javascript" src="<?PHP echo $media_url;?>system/js/jquery.wysiwyg.js"></script>
        
        <!--[if IE 6]>
        <script type="text/javascript" src="<?PHP echo $media_url;?>system/js/DD_belatedPNG_0.0.7a.js"></script>
        <script type="text/javascript">
        	DD_belatedPNG.fix('.png_bg, img, li');
        </script>
        <![endif]-->

        <?PHP if(FRAMEWORK_APPLICATION_ENV != 'production'): ?>
        <div id="server_badge" class="<?PHP echo FRAMEWORK_APPLICATION_ENV; ?>"><?PHP echo FRAMEWORK_APPLICATION_ENV; ?></div>
        <?PHP endif; ?>
    </body>
</html>