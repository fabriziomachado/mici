$(document).ready(function(){

    //Sidebar Accordion Menu:

    $('input, select, textarea').attr('autocomplete', 'off'); // Disable Autocomplete on all forms
    $('input, textarea').attr('spellcheck', true); // Enable Spell Checker for Text Areas

    $("#main-nav li ul").hide(); // Hide all sub menus
    $("#main-nav li a.current").parent().find("ul").slideToggle("slow"); // Slide down the current menu item's sub menu

    $("#main-nav li a.nav-top-item").click( // When a top menu item is clicked...
        function () {
            $(this).parent().siblings().find("ul").slideUp("normal"); // Slide up all sub menus except the one clicked
            $(this).next().slideToggle("normal"); // Slide down the clicked sub menu
            return false;
        }
    );

    $("#main-nav li a.no-submenu").click( // When a menu item with no sub menu is clicked...
        function () {
            window.location.href=(this.href); // Just open the link instead of a sub menu
            return false;
        }
    );

    // Sidebar Accordion Menu Hover Effect:

    $("#main-nav li .nav-top-item").hover(
        function () {
            if($(this).hasClass('current'))
            {
            // do nothing
            }
            else
            {
                $(this).stop().animate({
                    paddingRight: "25px"
                }, 200);
            }
        },
        function () {
            if($(this).hasClass('current'))
            {
            // do nothing
            }
            else
            {
                $(this).stop().animate({
                    paddingRight: "15px"
                });
            }
        }
    );

    //Minimize Content Box

    $(".content-box-header h3").css({
        "cursor":"s-resize"
    }); // Give the h3 in Content Box Header a different cursor
    $(".closed-box .content-box-content").hide(); // Hide the content of the header if it has the class "closed"
    $(".closed-box .content-box-tabs").hide(); // Hide the tabs in the header if it has the class "closed"

    $(".content-box-header h3").click( // When the h3 is clicked...
        function () {
            $(this).parent().next().toggle(); // Toggle the Content Box
            $(this).parent().parent().toggleClass("closed-box"); // Toggle the class "closed-box" on the content box
            $(this).parent().find(".content-box-tabs").toggle(); // Toggle the tabs
        }
    );

    // Content box tabs:

    $('.content-box .content-box-content div.tab-content').hide(); // Hide the content divs
    $('ul.content-box-tabs li a.default-tab').addClass('current'); // Add the class "current" to the default tab
    $('.content-box-content div.default-tab').show(); // Show the div with class "default-tab"

    $('.content-box ul.content-box-tabs li a').click( // When a tab is clicked...
        function() {
            $(this).parent().siblings().find("a").removeClass('current'); // Remove "current" class from all tabs
            $(this).addClass('current'); // Add class "current" to clicked tab
            var currentTab = $(this).attr('href'); // Set variable "currentTab" to the value of href of clicked tab
            $(currentTab).siblings().hide(); // Hide all content divs
            $(currentTab).show(); // Show the content div with the id equal to the id of clicked tab
            $(this).parent().parent().parent().find("h3").html($(this).text());
        //return false;
        }
    );

    if(location.hash != '')
    {
        $('a[href="'+location.hash+'"]').click();
    }

    //Close button:

    $(".close").click(
        function () {
            $(this).parent().fadeTo(400, 0, function () { // Links with the class "close" will close parent
                $(this).slideUp(400);
            });
            return false;
        }
    );

    // Alternating table rows:

    $('tbody tr:even').addClass("alt-row"); // Add class "alt-row" to even table rows

    // Check all checkboxes when the one in a table head is checked:

    $('.check-all').click(
        function(){
            $(this).parent().parent().parent().parent().find("input[type='checkbox']").attr('checked', $(this).is(':checked'));
        }
    )

    // Initialise Facebox Modal window:

    //$('a[rel*=modal]').facebox(); // Applies modal window to any link with attribute rel="modal"

    // Initialise jQuery WYSIWYG:
    $(".wysiwyg").wysiwyg({
        controls : {
            insertImage : {
                visible : false
            },
            h1mozilla : {
                visible : false
            },
            h2mozilla : {
                visible : false
            },
            h3mozilla : {
                visible : false
            },
            h1 : {
                visible : false
            },
            h2 : {
                visible : false
            },
            h3 : {
                visible : false
            },
            increaseFontSize : {
                visible : false
            },
            decreaseFontSize : {
                visible : false
            },
            underline     : {
                visible : true
            },
            createLink    :  {
                visible : true
            },
            undo : {
                visible : true
            },
            redo : {
                visible : true
            },
            html : {
                visible : true
            },
            removeFormat : {
                visible : true
            },

            separator00 : {
                visible : true,
                separator : true
            },
            separator01 : {
                visible : false,
                separator : true
            },
            separator02 : {
                visible : false,
                separator : true
            },
            separator03 : {
                visible : false,
                separator : true
            },
            separator04 : {
                visible : false,
                separator : true
            },
            separator05 : {
                visible : false,
                separator : true
            },
            separator06 : {
                visible : true,
                separator : true
            },
            separator07 : {
                visible : false,
                separator : true
            },
            separator08 : {
                visible : false,
                separator : true
            },
            separator09 : {
                visible : false,
                separator : true
            }
        }
    });

    $(".wysiwyg .bold").attr('title', 'Bold');
    $(".wysiwyg .italic").attr('title', 'Italic');
    $(".wysiwyg .underline").attr('title', 'Underline');
    $(".wysiwyg .undo").attr('title', 'Undo').css({
        'margin-top' : '1px'
    });
    $(".wysiwyg .redo").attr('title', 'Redo').css({
        'margin-top' : '-1px'
    });
    $(".wysiwyg .createLink").attr('title', 'Create Link');
    $(".wysiwyg .html").attr('title', 'HTML Code');
    $(".wysiwyg .removeFormat").attr('title', 'Remove Format');

    $('#pageloading').fadeOut('slow');

    $('.button, input[type="checkbox"]').click(
        function(){
            $(this).blur();
        }
    );
});

window.onbeforeunload = function()
{
    $('#pageloading').fadeIn('slow');
}

function notification(id, status, text, type)
{
    if(id)
    {
        if(status == 'open')
        {
            setTimeout(
                function()
                {
                    $(id).slideDown(400,
                        function()
                        {
                            $(this).fadeTo(400, 1);
                            $(id+' div').html(text);
                        }
                        ).addClass(type);
                },
                200
                );
        }
        else if(status == 'close')
        {
            $(id).fadeTo(0, 0,
                function()
                {
                    $(this).slideUp(0);
                    $(id+' div').html('');
                }
                ).removeClass('attention').removeClass('error').removeClass('information').removeClass('success');
        }
    }
}

var dateFormat = function () {
    var	token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,
    timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
    timezoneClip = /[^-+\dA-Z]/g,
    pad = function (val, len) {
        val = String(val);
        len = len || 2;
        while (val.length < len) val = "0" + val;
        return val;
    };

    // Regexes and supporting functions are cached through closure
    return function (date, mask, utc) {
        var dF = dateFormat;

        // You can't provide utc if you skip other args (use the "UTC:" mask prefix)
        if (arguments.length == 1 && Object.prototype.toString.call(date) == "[object String]" && !/\d/.test(date)) {
            mask = date;
            date = undefined;
        }

        // Passing date through Date applies Date.parse, if necessary
        date = date ? new Date(date) : new Date;
        if (isNaN(date)) throw SyntaxError("invalid date");

        mask = String(dF.masks[mask] || mask || dF.masks["default"]);

        // Allow setting the utc argument via the mask
        if (mask.slice(0, 4) == "UTC:") {
            mask = mask.slice(4);
            utc = true;
        }

        var	_ = utc ? "getUTC" : "get",
        d = date[_ + "Date"](),
        D = date[_ + "Day"](),
        m = date[_ + "Month"](),
        y = date[_ + "FullYear"](),
        H = date[_ + "Hours"](),
        M = date[_ + "Minutes"](),
        s = date[_ + "Seconds"](),
        L = date[_ + "Milliseconds"](),
        o = utc ? 0 : date.getTimezoneOffset(),
        flags = {
            d:    d,
            dd:   pad(d),
            ddd:  dF.i18n.dayNames[D],
            dddd: dF.i18n.dayNames[D + 7],
            m:    m + 1,
            mm:   pad(m + 1),
            mmm:  dF.i18n.monthNames[m],
            mmmm: dF.i18n.monthNames[m + 12],
            yy:   String(y).slice(2),
            yyyy: y,
            h:    H % 12 || 12,
            hh:   pad(H % 12 || 12),
            H:    H,
            HH:   pad(H),
            M:    M,
            MM:   pad(M),
            s:    s,
            ss:   pad(s),
            l:    pad(L, 3),
            L:    pad(L > 99 ? Math.round(L / 10) : L),
            t:    H < 12 ? "a"  : "p",
            tt:   H < 12 ? "am" : "pm",
            T:    H < 12 ? "A"  : "P",
            TT:   H < 12 ? "AM" : "PM",
            Z:    utc ? "UTC" : (String(date).match(timezone) || [""]).pop().replace(timezoneClip, ""),
            o:    (o > 0 ? "-" : "+") + pad(Math.floor(Math.abs(o) / 60) * 100 + Math.abs(o) % 60, 4),
            S:    ["th", "st", "nd", "rd"][d % 10 > 3 ? 0 : (d % 100 - d % 10 != 10) * d % 10]
        };

        return mask.replace(token, function ($0) {
            return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
        });
    };
}();

// Some common format strings
dateFormat.masks = {
    "default":      "ddd mmm dd yyyy HH:MM:ss",
    shortDate:      "m/d/yy",
    mediumDate:     "mmm d, yyyy",
    longDate:       "mmmm d, yyyy",
    fullDate:       "dddd, mmmm d, yyyy",
    shortTime:      "h:MM TT",
    mediumTime:     "h:MM:ss TT",
    longTime:       "h:MM:ss TT Z",
    isoDate:        "yyyy-mm-dd",
    isoTime:        "HH:MM:ss",
    isoDateTime:    "yyyy-mm-dd'T'HH:MM:ss",
    isoUtcDateTime: "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
};

// Internationalization strings
dateFormat.i18n = {
    dayNames: [
    "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat",
    "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
    ],
    monthNames: [
    "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
    "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
    ]
};

// For convenience...
Date.prototype.format = function (mask, utc) {
    return dateFormat(this, mask, utc);
};

function pad(number, length) {

    var str = '' + number;
    while (str.length < length) {
        str = '0' + str;
    }

    return str;
}

function new_window(mypage, myname, w, h, scroll)
{
    var winl = (screen.width - w) / 2;
    var wint = (screen.height - h) / 2;
    var winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',toolbar=no,status=no,resizable=yes,menubar=no,location=no';
    fb_win = window.open(mypage, myname, winprops);
}