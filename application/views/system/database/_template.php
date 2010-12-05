<h2>Welcome <?PHP echo $name; ?></h2>
<p id="page-intro">What would you like to do?</p>

<ul class="shortcut-buttons-set">
    <li><a class="shortcut-button" href="<?PHP echo base_url(); ?>system/statistics"><span><img src="<?PHP echo $media_url;?>system/images/icons/64x64/statistics_64.png" alt="icon" /><br />Statistics</span></a></li>
    <li><a class="shortcut-button" href="<?PHP echo base_url(); ?>system/database/schema"><span><img src="<?PHP echo $media_url;?>system/images/icons/64x64/database_64.png" alt="icon" /><br />Database</span></a></li>
    <li><a class="shortcut-button" href="<?PHP echo base_url(); ?>system/logs/php"><span><img src="<?PHP echo $media_url;?>system/images/icons/64x64/event_64.png" alt="icon" /><br />Event Logs</span></a></li>
    <li><a class="shortcut-button" href="<?PHP echo base_url(); ?>system/settings"><span><img src="<?PHP echo $media_url;?>system/images/icons/64x64/settings_64.png" alt="icon" /><br />Settings</span></a></li>
    <li><a class="shortcut-button" href="#messages" rel="modal" style="position: relative;"><span><img src="<?PHP echo $media_url;?>system/images/icons/64x64/warnings_64.png" alt="icon" /><br />Alerts</span><span id="alerts">3</span></a></li>
</ul>

<div class="clear"></div>

<div class="content-box">
    <div class="content-box-header">
        <h3>Content box</h3>

        <ul class="content-box-tabs">
            <li><a href="#tab1" class="default-tab">Table</a></li>
            <li><a href="#tab2">Forms</a></li>
        </ul>

        <div class="clear"></div>

    </div>

    <div class="content-box-content">
        <div class="tab-content default-tab" id="tab1">
            <div class="notification attention png_bg">
                <a href="#" class="close"><img src="<?PHP echo $media_url;?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <div>This is a Content Box. You can put whatever you want in it. By the way, you can close this notification with the top-right cross.</div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th><input class="check-all" type="checkbox" /></th>
                        <th>Column 1</th>
                        <th>Column 2</th>
                        <th>Column 3</th>
                        <th>Column 4</th>
                        <th>Column 5</th>
                    </tr>
                </thead>
                
                <tfoot>
                    <tr>
                        <td colspan="6">
                            <div class="bulk-actions align-left">
                                <select name="dropdown">
                                <option value="option1">Choose an action...</option>
                                <option value="option2">Edit</option>
                                <option value="option3">Delete</option>
                                </select>
                                <a class="button" href="#">Apply to selected</a>
                            </div>
                            
                            <div class="pagination">
                                <a href="#" title="First Page">&laquo; First</a><a href="#" title="Previous Page">&laquo; Previous</a>
                                <a href="#" class="number" title="1">1</a>
                                <a href="#" class="number" title="2">2</a>
                                <a href="#" class="number current" title="3">3</a>
                                <a href="#" class="number" title="4">4</a>
                                <a href="#" title="Next Page">Next &raquo;</a><a href="#" title="Last Page">Last &raquo;</a>
                            </div>
                            <div class="clear"></div>
                        </td>
                    </tr>
                </tfoot>
                
                <tbody>
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>Lorem ipsum dolor</td>
                        <td><a href="#" title="title">Sit amet</a></td>
                        <td>Consectetur adipiscing</td>
                        <td>Donec tortor diam</td>
                        <td>
                            <a href="#" title="Edit"><img src="<?PHP echo $media_url;?>system/images/icons/pencil.png" alt="Edit" /></a>
                            <a href="#" title="Delete"><img src="<?PHP echo $media_url;?>system/images/icons/cross.png" alt="Delete" /></a> 
                            <a href="#" title="Edit Meta"><img src="<?PHP echo $media_url;?>system/images/icons/hammer_screwdriver.png" alt="Edit Meta" /></a>
                        </td>
                    </tr>
                    
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>Lorem ipsum dolor</td>
                        <td><a href="#" title="title">Sit amet</a></td>
                        <td>Consectetur adipiscing</td>
                        <td>Donec tortor diam</td>
                        <td>
                            <a href="#" title="Edit"><img src="<?PHP echo $media_url;?>system/images/icons/pencil.png" alt="Edit" /></a>
                            <a href="#" title="Delete"><img src="<?PHP echo $media_url;?>system/images/icons/cross.png" alt="Delete" /></a> 
                            <a href="#" title="Edit Meta"><img src="<?PHP echo $media_url;?>system/images/icons/hammer_screwdriver.png" alt="Edit Meta" /></a>
                        </td>
                    </tr>
                    
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>Lorem ipsum dolor</td>
                        <td><a href="#" title="title">Sit amet</a></td>
                        <td>Consectetur adipiscing</td>
                        <td>Donec tortor diam</td>
                        <td>
                            <a href="#" title="Edit"><img src="<?PHP echo $media_url;?>system/images/icons/pencil.png" alt="Edit" /></a>
                            <a href="#" title="Delete"><img src="<?PHP echo $media_url;?>system/images/icons/cross.png" alt="Delete" /></a> 
                            <a href="#" title="Edit Meta"><img src="<?PHP echo $media_url;?>system/images/icons/hammer_screwdriver.png" alt="Edit Meta" /></a>
                        </td>
                    </tr>
                    
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>Lorem ipsum dolor</td>
                        <td><a href="#" title="title">Sit amet</a></td>
                        <td>Consectetur adipiscing</td>
                        <td>Donec tortor diam</td>
                        <td>
                            <a href="#" title="Edit"><img src="<?PHP echo $media_url;?>system/images/icons/pencil.png" alt="Edit" /></a>
                            <a href="#" title="Delete"><img src="<?PHP echo $media_url;?>system/images/icons/cross.png" alt="Delete" /></a> 
                            <a href="#" title="Edit Meta"><img src="<?PHP echo $media_url;?>system/images/icons/hammer_screwdriver.png" alt="Edit Meta" /></a>
                        </td>
                    </tr>
                    
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>Lorem ipsum dolor</td>
                        <td><a href="#" title="title">Sit amet</a></td>
                        <td>Consectetur adipiscing</td>
                        <td>Donec tortor diam</td>
                        <td>
                            <a href="#" title="Edit"><img src="<?PHP echo $media_url;?>system/images/icons/pencil.png" alt="Edit" /></a>
                            <a href="#" title="Delete"><img src="<?PHP echo $media_url;?>system/images/icons/cross.png" alt="Delete" /></a> 
                            <a href="#" title="Edit Meta"><img src="<?PHP echo $media_url;?>system/images/icons/hammer_screwdriver.png" alt="Edit Meta" /></a>
                        </td>
                    </tr>
                    
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>Lorem ipsum dolor</td>
                        <td><a href="#" title="title">Sit amet</a></td>
                        <td>Consectetur adipiscing</td>
                        <td>Donec tortor diam</td>
                        <td>
                            <a href="#" title="Edit"><img src="<?PHP echo $media_url;?>system/images/icons/pencil.png" alt="Edit" /></a>
                            <a href="#" title="Delete"><img src="<?PHP echo $media_url;?>system/images/icons/cross.png" alt="Delete" /></a> 
                            <a href="#" title="Edit Meta"><img src="<?PHP echo $media_url;?>system/images/icons/hammer_screwdriver.png" alt="Edit Meta" /></a>
                        </td>
                    </tr>
                    
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>Lorem ipsum dolor</td>
                        <td><a href="#" title="title">Sit amet</a></td>
                        <td>Consectetur adipiscing</td>
                        <td>Donec tortor diam</td>
                        <td>
                            <a href="#" title="Edit"><img src="<?PHP echo $media_url;?>system/images/icons/pencil.png" alt="Edit" /></a>
                            <a href="#" title="Delete"><img src="<?PHP echo $media_url;?>system/images/icons/cross.png" alt="Delete" /></a> 
                            <a href="#" title="Edit Meta"><img src="<?PHP echo $media_url;?>system/images/icons/hammer_screwdriver.png" alt="Edit Meta" /></a>
                        </td>
                    </tr>
                    
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>Lorem ipsum dolor</td>
                        <td><a href="#" title="title">Sit amet</a></td>
                        <td>Consectetur adipiscing</td>
                        <td>Donec tortor diam</td>
                        <td>
                            <a href="#" title="Edit"><img src="<?PHP echo $media_url;?>system/images/icons/pencil.png" alt="Edit" /></a>
                            <a href="#" title="Delete"><img src="<?PHP echo $media_url;?>system/images/icons/cross.png" alt="Delete" /></a> 
                            <a href="#" title="Edit Meta"><img src="<?PHP echo $media_url;?>system/images/icons/hammer_screwdriver.png" alt="Edit Meta" /></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="tab-content" id="tab2">

            <form action="" method="post">
                <fieldset>
                    <p>
                        <label>Small form input</label>
                        <input class="text-input small-input" type="text" id="small-input" name="small-input" /> <span class="input-notification success png_bg">Successful message</span>
                        <br /><small>A small description of the field</small>
                    </p>
                    <p>
                        <label>Medium form input</label>
                        <input class="text-input medium-input" type="text" id="medium-input" name="medium-input" /> <span class="input-notification error png_bg">Error message</span>
                    </p>
                    <p>
                        <label>Large form input</label>
                        <input class="text-input large-input" type="text" id="large-input" name="large-input" />
                    </p>
                    <p>
                        <label>Checkboxes</label>
                        <input type="checkbox" name="checkbox1" /> This is a checkbox <input type="checkbox" name="checkbox2" /> And this is another checkbox
                    </p>
                    <p>
                        <label>Radio buttons</label>
                        <input type="radio" name="radio1" /> This is a radio button<br />
                        <input type="radio" name="radio2" /> This is another radio button
                    </p>
                    <p>
                        <label>This is a drop down list</label>              
                        <select name="dropdown" class="small-input">
                            <option value="option1">Option 1</option>
                            <option value="option2">Option 2</option>
                            <option value="option3">Option 3</option>
                            <option value="option4">Option 4</option>
                        </select> 
                    </p>
                    <p>
                        <label>Textarea with WYSIWYG</label>
                        <textarea class="text-input textarea wysiwyg" id="textarea" name="textfield" cols="79" rows="15"></textarea>
                    </p>
                    <p>
                        <input class="button" type="submit" value="Submit" />
                    </p>
                </fieldset>
                
                <div class="clear"></div>
                
            </form>

        </div>
    </div>
</div>

<div class="content-box column-left">
    <div class="content-box-header">
        <h3>Cool Chart</h3>
    </div>

    <div class="content-box-content">
        <div class="tab-content default-tab">
            <h4 id="hoverdata">Mouse hovers at (<span id="x">0</span>, <span id="y">0</span>). <span id="clickdata">&nbsp;</span></h4>
            <div id="placeholder" style="width:100%;height:300px" class="flotchart"></div>
        </div>
    </div>
</div>

<div class="content-box column-right closed-box">
    <div class="content-box-header">

        <h3>Content box right</h3>

    </div>

    <div class="content-box-content">
        <div class="tab-content default-tab">

            <h4>This box is closed by default</h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed in porta lectus. Maecenas dignissim enim quis ipsum mattis aliquet. Maecenas id velit et elit gravida bibendum. Duis nec rutrum lorem. Donec egestas metus a risus euismod ultricies. Maecenas lacinia orci at neque commodo commodo.</p>

        </div>
    </div>
</div>

<div class="clear"></div>

<div class="notification attention png_bg">
    <a href="#" class="close"><img src="<?PHP echo $media_url;?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
    <div>Attention notification. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin vulputate, sapien quis fermentum luctus, libero.</div>
</div>

<div class="notification information png_bg">
    <a href="#" class="close"><img src="<?PHP echo $media_url;?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
    <div>Information notification. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin vulputate, sapien quis fermentum luctus, libero.</div>
</div>

<div class="notification success png_bg">
    <a href="#" class="close"><img src="<?PHP echo $media_url;?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
    <div>Success notification. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin vulputate, sapien quis fermentum luctus, libero.</div>
</div>

<div class="notification error png_bg">
    <a href="#" class="close"><img src="<?PHP echo $media_url;?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
    <div>Error notification. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin vulputate, sapien quis fermentum luctus, libero.</div>
</div>
        
<script type="text/javascript" src="<?PHP echo $media_url;?>system/js/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="<?PHP echo $media_url;?>system/js/flot/jquery.flot.crosshair.min.js"></script>

<!--[if IE]>
<script type="text/javascript" language="javascript" src="<?PHP echo $media_url;?>system/js/flot/excanvas.min.js"></script>
<![endif]-->

<script id="source" language="javascript" type="text/javascript">
$(function (){
	var sin = [], cos = [];
	for (var i = 0; i < 14; i += 0.5)
	{
		sin.push([i, Math.sin(i)]);
		cos.push([i, Math.cos(i)]);
	}

	drawplot(sin, cos);
	
	$(window).resize(function(){
		drawplot(sin, cos);
	});

	function showTooltip(x, y, contents)
	{
		$('<div id="tooltip">' + contents + '<\/div>').css(
			{
				position: 'absolute',
				display: 'none',
				top: y + -22,
				left: x + 2,
				border: '1px solid #a9d658',
				padding: '4px',
				color: '#000',
				'background-color': '#eff5e6',
				opacity: 0.80
			}
		).appendTo("body").fadeIn(200);
	}

	var previousPoint = null;
	$("#placeholder").bind("plothover", 
		function (event, pos, item)
		{
			$("#x").text(pos.x.toFixed(2));
			$("#y").text(pos.y.toFixed(2));

			if (item)
			{
				if (previousPoint != item.datapoint)
				{
					previousPoint = item.datapoint;
					
					$("#tooltip").remove();
					
					var x = item.datapoint[0].toFixed(2), y = item.datapoint[1].toFixed(2);
				
					showTooltip(item.pageX, item.pageY, item.series.label + " of " + x + " = " + y);
				}
			}
		}
	);

	$("#placeholder").bind("plotclick", 
		function (event, pos, item)
		{
			if (item)
			{
				$("#clickdata").text("You clicked point " + item.dataIndex + " in " + item.series.label + ".");
				plot.highlight(item.series, item.datapoint);
			}
		}
	);
	
	function drawplot(sin, cos){
		var plot = $.plot($("#placeholder"),
			[
				{ 
					data: sin, 
					label: "sin(x)"
				}, 
				{ 
					data: cos, 
					label: "cos(x)" 
				}
			],
			{
				series:
				{
					lines:
					{
						show: true
					},
					points:
					{
						show: true
					}
				},
				crosshair:
				{
					mode: "xy", 
					color: "rgba(0, 0, 0, 0.20)"
				},
				grid:
				{
					hoverable: true, 
					clickable: true
				},
				yaxis:
				{
					min: -1.2,
					max: 1.2
				}
			}
		);	
	}
});
</script>