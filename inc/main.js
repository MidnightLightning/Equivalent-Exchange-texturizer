$(document).ready(function() {
	// Initialize Farbtastic color picker
	// http://acko.net/dev/farbtastic
	var $f = $.farbtastic('#color_picker'); // Create color picker
	var $p = $('#color_picker').css('opacity', 0.25); // Dim container by default
	var selected_dust_color; // Currently-selected color field
	$('input.color')
		.each(function() {
			$f.linkTo(this);
			$(this).css('opacity', 0.75); // Dim by default
		})
		.focus(function() {
			// On focus, set to active
			if (selected_dust_color) {
				// Already had one selected
				$(selected_dust_color)
					.css('opacity', 0.75)
					.removeClass('selected');
			}
			$f.linkTo(this); // Point picker to this field
			$p.css('opacity', 1); // Un-dim picker
			$(selected_dust_color = this)
				.css('opacity', 1)
				.addClass('selected');
		});
		$f.linkTo(function() {}); // Start off unlinked

		// Dust preview image
		var dust_preview_old = false; // Is the preview image up-to-date?
		$('#dust_preview_image').click(function(e) {
				if (dust_preview_old) {
					update_dust_preview(); // Update the preview image
				}
			});
		$('#color_picker').bind('colorChange', function(e, c) {
			// Color has been updated; invalidate the preview image
			dust_preview_old = true;
			$('#dust_preview_image').css('opacity', 0.25).css('cursor', 'pointer');
		});

});

function update_dust_preview() {
	var new_src = 'img/dust_preview.php?';
	new_src += 't='+$('input[name=dust_type]:checked').val();
	new_src += '&c='+$('input[name=dust_low_color]').val().substr(1)+','+$('input[name=dust_med_color]').val().substr(1)+','+$('input[name=dust_hi_color]').val().substr(1);
	$('#dust_preview_image').attr('src', new_src).css('opacity', 1).css('cursor', '');
	dust_preview_old = false;
}