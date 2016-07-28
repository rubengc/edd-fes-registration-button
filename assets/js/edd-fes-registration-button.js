(function ($) {
	$('.edd-fes-registration-button-form').on('submit', function(e) {
		e.preventDefault();

		var form = $(this),
			submit_button = form.find('input[type=submit]'),
			form_data = edd_fes_registration_button_validate_form(form);

		console.log();

		if (form_data) {
				submit_button.attr('disabled', 'disabled').addClass('button-primary-disabled');
				$.post(registration_button.ajax.url, form_data, function (response) {
					if (response.success) {
						var title = '';
						var message = '';
						if ( response.title ){
							title = response.title;
						}

						if ( response.message ){
							message = response.message;
						}
						if ( response.skipswal ){
							overlay.hide();
							 if ( response.redirect_to !== '#' ){
									window.location = response.redirect_to;
								}
						} else {
							overlay.hide();
							swal({ 
								title: title,
								text: message,
								html: true,
								allowEscapeKey : false,
								type: "success"
							},
							function(){
								if ( response.redirect_to !== '#' ){
										window.location.href = response.redirect_to;
								} else {
									submit_button.removeClass('button-primary-disabled');
									form.find('span.fes-loading').remove();
									submit_button.removeAttr('disabled'); // undisable the submit button
								}
							} );
						}
					} else {
						var errors = response.errors;
						var title = '';
						var message = '';
						if ( response.title ){
							title = response.title;
						}

						if ( response.message ){
							message = response.message;
						}

						overlay.hide(); // hide loading overlay

						// show error overlay
						swal({
							title: title,
							text: message,
							html: true,
							type: "error"
						});

						submit_button.removeAttr('disabled'); // undisable the submit button
					}
					submit_button.removeClass('button-primary-disabled');
					form.find('span.fes-loading').remove();
				})
				.fail( function(xhr, textStatus, errorThrown) {
						var title = '';
						var message = '';
						title = 'Could not connect';
						message = $(xhr.responseponseText).text();
						console.log( message );
						message = message.substring(0, message.indexOf("Call Stack"));
						overlay.hide(); // hide loading overlay
						// show error overlay
						swal({
							title: title,
							text: message,
							html: true,
							type: "error"
						}); 
						submit_button.removeAttr('disabled'); // undisable the submit button
						submit_button.removeClass('button-primary-disabled');
						form.find('span.fes-loading').remove();
				}); 
		}
	});

	function edd_fes_registration_button_validate_form(form) {
		var temp,
		form_data = form.serialize(),
		rich_texts = [];

		// grab rich texts from tinyMCE
		$('.fes-rich-validation').each(function (index, item) {
			temp = $(item).data('id');
			val = $.trim(tinyMCE.get(temp).getContent());
			rich_texts.push(temp + '=' + encodeURIComponent(val));
		});

		// append them to the form var
		form_data = form_data + '&' + rich_texts.join('&');
		return form_data;
	}
})(jQuery);