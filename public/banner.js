/*
Mobile Banner is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Mobile Banner is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Mobile Banner. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

/*
JavaScript to add the banner
mobilebanner_opts array is passed from PHP.
*/

jQuery(document).ready(function() {
	// Display banner if screen is narrower than set width, and text is set
	if (jQuery(window).width() < mobilebanner_opts['width'] &&
		mobilebanner_opts['text'] != '') {
			// Build up HTML: Div
			div = '<div id="mobile-banner-divid" class="mobile-banner-div" ' +
				'style="background:' + mobilebanner_opts['bgcolour'] + ';'
			if (mobilebanner_opts['height'] != '' && mobilebanner_opts['height'] != 0) {
				div += 'height:' + mobilebanner_opts['height'] + 'px;'
				// Set line-height so that text is vertically centred
				div += 'line-height:' + mobilebanner_opts['height'] + 'px;'
			}
			div +=
				'">'
			// Paragraph
			div += '<p class="mobile-banner-p" style="color:' +
				mobilebanner_opts['textcolour'] + ';">'
			// Close button
			if (mobilebanner_opts['closebutton'] == 1)
				div += '<img id="mobile-banner-close" src="' + mobilebanner_opts['directory'] + 'close.png" alt="Close this banner" title="Close">'
			// Link
			if (mobilebanner_opts['url'] != '') {
				div += '<a ';
				div += 'style="color:' + mobilebanner_opts['textcolour'] + ';" ';
				div += 'href="' + mobilebanner_opts['url'] + '" ';
				if (mobilebanner_opts['newtab'] == 1) {
					div += 'target="_blank" ';
				}
				div +='>';
			}
			div += mobilebanner_opts['text'];
			if (mobilebanner_opts['url'] != '')
				div += '</a>'
			div += '</p></div>'
			// Append the div to the body
			jQuery("body").append(div)
	}
	
	jQuery('#mobile-banner-close').click(function() {
		// Hide the banner DIV
		jQuery('#mobile-banner-divid').hide()

		// Set the cookie to not hide the banner again
		// Set expiry date
		if (mobilebanner_opts['cookiedays'] == 0 || mobilebanner_opts['cookiedays'] == '')
			expires = ''
		else {
			var date = new Date()
			date.setTime(date.getTime()+(mobilebanner_opts['cookiedays']*24*60*60*1000))
			expires = "; expires="+date.toGMTString()
		}
		// Set the cookie
		document.cookie = 'mobilebanner=hide' + expires
    })
})
