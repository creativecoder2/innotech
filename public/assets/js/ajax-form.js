$(function() {

	// Generic AJAX submission handler for all contact & appointment forms
	$(document).on('submit', '#contact-form, #homeContactForm, .ajax-contact-form', function(e) {
		// Stop the browser from refreshing / navigating away
		e.preventDefault();

		var form = $(this);
		var submitBtn = form.find('button[type="submit"]');
		var origBtnHtml = submitBtn.html();

		// Locate form-specific message container
		var formMessages = form.find('.ajax-response');
		if (!formMessages.length) {
			formMessages = form.siblings('.ajax-response');
		}
		if (!formMessages.length) {
			formMessages = $('.ajax-response');
		}

		// Set button loading state
		submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="width: 1rem; height: 1rem;"></span> Sending...');
		formMessages.hide().empty().removeClass('error success');

		// Serialize the form data
		var formData = form.serialize();

		// Submit the form using AJAX
		$.ajax({
			type: 'POST',
			url: form.attr('action'),
			data: formData,
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'Accept': 'application/json'
			}
		})
		.done(function(response) {
			submitBtn.prop('disabled', false).html(origBtnHtml);
			formMessages.removeClass('error').addClass('success');

			var msg = (response && response.message) ? response.message : 'Thank you! Your message has been sent successfully. Our biomedical team will contact you shortly.';
			formMessages.html(`
				<div class="alert alert-success d-flex align-items-center p-3 rounded-3 shadow-sm border-0" style="background-color: #ECFDF5; border-left: 5px solid #10B981 !important; color: #065F46; margin: 15px 0;">
					<i class="fa-solid fa-circle-check fs-4 me-3 text-success"></i>
					<div>
						<strong class="d-block mb-1" style="font-size: 14.5px; color: #065F46;">Message Sent Successfully!</strong>
						<span style="font-size: 13.5px; opacity: 0.95;">${msg}</span>
					</div>
				</div>
			`).slideDown(300);

			// Clear the form fields (preserve CSRF token)
			form.find('input:not([name="_token"]):not([type="hidden"]), textarea').val('');
		})
		.fail(function(xhr) {
			submitBtn.prop('disabled', false).html(origBtnHtml);
			formMessages.removeClass('success').addClass('error');

			// Check for 2-hour suspension limit (HTTP 429)
			if (xhr.status === 429 || (xhr.responseJSON && xhr.responseJSON.suspended)) {
				var suspendedMsg = (xhr.responseJSON && xhr.responseJSON.message)
					? xhr.responseJSON.message
					: 'Security Alert: Maximum submission attempts exceeded. This email address has been suspended for 2 hours.';

				formMessages.html(`
					<div class="alert alert-danger p-3 rounded-3 shadow-sm border-0" style="background-color: #FEF2F2; border-left: 5px solid #EF4444 !important; color: #7F1D1D; margin: 15px 0;">
						<div style="font-weight: 700; color: #B91C1C; font-size: 14px; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
							<i class="fa-solid fa-clock-rotate-left" style="color: #DC2626;"></i> Temporary 2-Hour Suspension
						</div>
						<div style="color: #7F1D1D; font-size: 13px; line-height: 1.5; font-weight: 500;">
							${suspendedMsg}
						</div>
					</div>
				`).slideDown(300);
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

			formMessages.html(`
				<div class="alert alert-danger d-flex align-items-center p-3 rounded-3 shadow-sm border-0" style="background-color: #FEF2F2; border-left: 5px solid #EF4444 !important; color: #7F1D1D; margin: 15px 0;">
					<i class="fa-solid fa-circle-exclamation fs-4 me-3 text-danger"></i>
					<div style="font-size: 13.5px; font-weight: 500; line-height: 1.5;">
						${errMsg}
					</div>
				</div>
			`).slideDown(300);
		});
	});

});
