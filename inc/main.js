$(document).ready(function() {
	// Initialize Farbtastic color picker
	// http://acko.net/dev/farbtastic
	var f = $.farbtastic('#color_picker'); // Create color picker
	var p = $('#color_picker').css('opacity', 0.25); // Dim container by default
	var selected; // Currently-selected color field
	$('input.color')
		.each(function() {
			f.linkTo(this);
			$(this).css('opacity', 0.75); // Dim by default
		})
		.focus(function() {
			// On focus, set to active
			if (selected) {
				// Already had one selected
				$(selected)
					.css('opacity', 0.75)
					.removeClass('selected');
			}
			f.linkTo(this); // Point picker to this field
			p.css('opacity', 1); // Un-dim picker
			$(selected = this)
				.css('opacity', 1)
				.addClass('selected');
		})
});