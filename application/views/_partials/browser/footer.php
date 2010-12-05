            <div class="push"></div>
        </div><!-- END OF CENTER_COLUMN -->

        <!-- FOOTER -->
        <?PHP echo $footer_menu; ?>

        <script type="text/javascript" src="<?PHP echo $media_url; ?>browser/js/jquery.js"></script>
        <script type="text/javascript" src="<?PHP echo $media_url; ?>browser/js/jquery.easing.min.js"></script>
        <script type="text/javascript" src="<?PHP echo $media_url; ?>browser/js/jquery.scrollto.min.js"></script>
        <script type="text/javascript" src="<?PHP echo $media_url; ?>browser/js/hoverIntent.js"></script>
        <script type="text/javascript" src="<?PHP echo $media_url; ?>browser/js/ajaxform.js"></script>
        <script type="text/javascript" src="<?PHP echo $media_url; ?>browser/js/inner.js"></script>

    <?PHP if($browser['ismobiledevice']): ?>
        <script type="text/javascript" src="<?PHP echo $media_url; ?>browser/js/mobile.js"></script>
    <?PHP endif; ?>

        <!--[if lt IE 7]>
        <script src="<?PHP echo $media_url; ?>browser/js/IE8.js" type="text/javascript"></script>
        <![endif]-->

        <a href="#bottom" accesskey="B"></a>
        <a name="bottom"></a>

    <?PHP if($section == 'portfolio' && $subsection == ''): ?>
        <!-- // This script is for the roll-overs on the thumbs  // -->
        <script type="text/javascript">
            $(document).ready(function(){
                //To switch directions up/down and left/right just place a "-" in front of the top/left attribute
                //Vertical Sliding
                $('.boxgrid.slidedown').hover(function(){
                    $(".cover", this).stop().animate({top:'-260px'},{queue:false,duration:300});
                }, function() {
                    $(".cover", this).stop().animate({top:'0px'},{queue:false,duration:300});
                });
                //Horizontal Sliding
                $('.boxgrid.slideright').hover(function(){
                    $(".cover", this).stop().animate({left:'325px'},{queue:false,duration:300});
                }, function() {
                    $(".cover", this).stop().animate({left:'0px'},{queue:false,duration:300});
                });
                //Diagnal Sliding
                $('.boxgrid.thecombo').hover(function(){
                    $(".cover", this).stop().animate({top:'260px', left:'325px'},{queue:false,duration:300});
                }, function() {
                    $(".cover", this).stop().animate({top:'0px', left:'0px'},{queue:false,duration:300});
                });
                //Partial Sliding (Only show some of background)
                $('.boxgrid.peek').hover(function(){
                    $(".cover", this).stop().animate({top:'100px'},{queue:false,duration:160});
                }, function() {
                    $(".cover", this).stop().animate({top:'0px'},{queue:false,duration:160});
                });
                //Full Caption Sliding (Hidden to Visible)
                $('.boxgrid.captionfull').hover(function(){
                    $(".cover", this).stop().animate({top:'90px'},{queue:false,duration:300});
                }, function() {
                    $(".cover", this).stop().animate({top:'220px'},{queue:false,duration:300});
                });
                //Caption Sliding (Partially Hidden to Visible)
                $('.boxgrid.caption').hover(function(){
                    $(".cover", this).stop().animate({top:'50px'},{queue:false,duration:160});
                }, function() {
                    $(".cover", this).stop().animate({top:'260px'},{queue:false,duration:160});
                });
            });
        </script>
    <?PHP elseif($section == 'portfolio' && $subsection == 'client'): ?>
        <script type="text/javascript" src="<?PHP echo $media_url; ?>browser/js/jquery.prettyPhoto.js" charset="utf-8"></script>
        <script type="text/javascript" charset="utf-8">
            $(document).ready(function(){
                $("a[rel^='prettyPhoto']").prettyPhoto({
                    animationSpeed: 'fast', /* fast/slow/normal */
                    padding: 40, /* padding for each side of the picture */
                    opacity: 0.80, /* Value betwee 0 and 1 */
                    showTitle: false, /* true/false */
                    allowresize: true, /* true/false */
                    counter_separator_label: ' / ', /* The separator for the gallery counter 1 "of" 2 */
                    theme: 'light_square', /* light_rounded / dark_rounded / light_square / dark_square */
                    callback: function(){}
                });
            });
        </script>
    <?PHP endif; ?>
    <?PHP if($ss_client != ''): ?>
        <script type="text/javascript">
            $.scrollTo( '#ss-<?PHP echo $ss_client; ?>', 800, {easing:'easeout'} );
        </script>
    <?PHP endif; ?>
    </body>
</html>