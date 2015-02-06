function adminCookieBar(url) {
    var adminbar =
        '<div id="wpadminbar" class="" role="navigation">'+
            '<a class="screen-reader-shortcut" href="#wp-toolbar" tabindex="1">Skip to toolbar</a>' +
            '<div class="quicklinks" id="wp-toolbar" role="navigation" aria-label="Top navigation toolbar." tabindex="0">' +
                '<ul id="wp-admin-bar-root-default" class="ab-top-menu">' +
        		    '<li id="wp-admin-bar-edit">'+
        		        '<a class="ab-item" href="'+url+'">Edit Page</a>' +
        		    '</li>' +
                '</ul>' +
            '</div>' +
        '</div>';

    var logged_in = getCookie("AdminCookieBar");
    if (logged_in != null && logged_in != "") {
        jQuery('body').append(adminbar);
    }
}

function getCookie(c_name) {
    var i,x,y,ARRcookies=document.cookie.split(";");
    for (i=0;i<ARRcookies.length;i++) {
        x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
        y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
        x=x.replace(/^\s+|\s+$/g,"");

        if (x==c_name) {
            return unescape(y);
        }
    }
}