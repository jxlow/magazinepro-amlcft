/**
 * This script below adds the smooth scrolling to the Magazine Pro theme.
 * https://sridharkatakam.com/set-smooth-scrolling-hash-links/
 * @package Magazine\JS
 * @author JXLOW
 * @license GPL-2.0+
 */

jQuery(function( $ ){

	// Smooth scrolling when clicking on a hash link
	$('a[href^="#"]').on('click',function (e) {
		e.preventDefault();

		var target = this.hash;
		var $target = $(target);

		if ( $(window).width() > 1023 ) {
			var $scrollTop = $target.offset().top -50;
		} else {
			var $scrollTop = $target.offset().top;
		}

		$('html, body').stop().animate({
			'scrollTop': $scrollTop
		}, 900, 'swing');
	});

});

function addLink() {
    //Get the selected text and append the extra info
    var selection = window.getSelection(),
        pagelink = '<br /><br /> Read more at: ' + document.location.href,
        copytext = selection + pagelink,
        newdiv = document.createElement('div');

    //hide the newly created container
    newdiv.style.position = 'absolute';
    newdiv.style.left = '-99999px';

    //insert the container, fill it with the extended text, and define the new selection
    document.body.appendChild(newdiv);
    newdiv.innerHTML = copytext;
    selection.selectAllChildren(newdiv);

    window.setTimeout(function () {
        document.body.removeChild(newdiv);
    }, 100);
}

document.addEventListener('copy', addLink);