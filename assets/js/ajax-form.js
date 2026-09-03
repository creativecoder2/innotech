$(function() {

	// Get the form.
	var form = $('#contact-form');

	// Get the messages div.
	var formMessages = $('.ajax-response');

	// Set up an event listener for the contact form.
	$(form).submit(function(e) {
		// Stop the browser from submitting the form.
		e.preventDefault();

		var submitBtn = $(form).find('button[type="submit"]');
		var origBtnText = submitBtn.html();
		submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Sending...');
		$(formMessages).empty().removeClass('error success');

		// Serialize the form data.
		var formData = $(form).serialize();

		// Submit the form using AJAX.
		$.ajax({
			type: 'POST',
			url: $(form).attr('action'),
			data: formData,
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'Accept': 'application/json'
			}
		})
		.done(function(response) {
			submitBtn.prop('disabled', false).html(origBtnText);
			$(formMessages).removeClass('error').addClass('success');

			var msg = (response && response.message) ? response.message : 'Thank you! Your message has been sent successfully.';
			$(formMessages).html(`
				<div style="background: #ECFDF5; border: 1.5px solid #10B981; border-radius: 8px; padding: 12px 16px; margin-top: 15px; color: #065F46; font-size: 14px; font-weight: 600;">
					<i class="fa-solid fa-circle-check text-success me-2 fs-5"></i> ${msg}
				</div>
			`);

			// Clear the form.
			$('#contact-form input, #contact-form textarea').val('');
		})
		.fail(function(xhr) {
			submitBtn.prop('disabled', false).html(origBtnText);
			$(formMessages).removeClass('success').addClass('error');

			// Check for 2-hour suspension limit (HTTP 429)
			if (xhr.status === 429 || (xhr.responseJSON && xhr.responseJSON.suspended)) {
				var suspendedMsg = (xhr.responseJSON && xhr.responseJSON.message)
					? xhr.responseJSON.message
					: 'Maximum attempts exceeded. This email address has been suspended for 2 hours.';

				$(formMessages).html(`
					<div style="background: #FEF2F2; border: 1.5px solid #F87171; border-radius: 8px; padding: 14px 18px; margin-top: 15px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
						<div style="font-weight: 700; color: #B91C1C; font-size: 13.5px; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
							<i class="fa-solid fa-clock-rotate-left" style="color: #DC2626;"></i> Temporary 2-Hour Suspension
						</div>
						<div style="color: #7F1D1D; font-size: 13px; line-height: 1.5; font-weight: 500;">
							${suspendedMsg}
						</div>
					</div>
				`);
				return;
			}

			// Validation / standard error
			var errMsg = 'Oops! An error occurred and your message could not be sent. Please check your fields.';
			if (xhr.responseJSON && xhr.responseJSON.errors) {
				var errs = [];
				for (var key in xhr.responseJSON.errors) {
					errs.push(xhr.responseJSON.errors[key][0]);
				}
				errMsg = errs.join('<br>');
			} else if (xhr.responseJSON && xhr.responseJSON.message) {
				errMsg = xhr.responseJSON.message;
			}

			$(formMessages).html(`
				<div style="background: #FEF2F2; border: 1.5px solid #F87171; border-radius: 8px; padding: 12px 16px; margin-top: 15px; color: #7F1D1D; font-size: 13px; font-weight: 500;">
					<i class="fa-solid fa-circle-exclamation text-danger me-2"></i> ${errMsg}
				</div>
			`);
		});
	});

});
