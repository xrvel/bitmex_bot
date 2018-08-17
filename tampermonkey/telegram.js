// ==UserScript==
// @name         Web Telegram
// @namespace    http://tampermonkey.net/
// @version      0.1
// @description  try to take over the world!
// @author       Xrvel
// @match        https://web.telegram.org/*
// @grant        none
// ==/UserScript==

var _tm_te_date, _tm_te_message;

(function() {
    'use strict';

    var script = document.createElement('script');
        script.onload = function() {
            jQuery.noConflict();
            if (callback) {
                callback(jQuery);
            }
        };
        script.src = "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js";
        document.getElementsByTagName('head')[0].appendChild(script);

    /* setInterval(function(){ console.log('hello'); }, 3000); */

    setTimeout(function() {
		setInterval(function(){
			jQuery('.im_content_message_wrap').each(function(i, obj) {
				_tm_te_date = jQuery(this).find('.im_message_date_text').attr('data-content');
				_tm_te_message = jQuery(this).find('.im_message_text').text();
				console.log('Date = '+_tm_te_date+'\nMessage = '+_tm_te_message+'\n\n\n');
			});
		}, 3000);
	}, 5000);
})();