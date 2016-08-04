(function ($) {
	$('.edd-fes-registration-button-form').on('submit', function(e) {
		e.preventDefault();

		var form = $(this),
			submit_button = form.find('input[type=submit]'),
			form_data = edd_fes_registration_button_validate_form(form);

		if ( fes_form.loading_icon != "" ){
			var overlay = fesSpinner({
				text: fes_form.loadingtext,
				icon: fes_form.loading_icon
			});
		} else {
			var opts = {
				lines: 13, // The number of lines to draw
				length: 11, // The length of each line
				width: 5, // The line thickness
				radius: 17, // The radius of the inner circle
				corners: 1, // Corner roundness (0..1)
				rotate: 0, // The rotation offset
				color: '#FFF', // #rgb or #rrggbb
				speed: 1, // Rounds per second
				trail: 60, // Afterglow percentage
				shadow: false, // Whether to render a shadow
				hwaccel: false, // Whether to use hardware acceleration
				className: 'fes_spinner', // The CSS class to assign to the spinner
				zIndex: 2e9, // The z-index (defaults to 2000000000)
				top: 'auto', // Top position relative to parent in px
				left: 'auto' // Left position relative to parent in px
			};

			var target = document.createElement("div");
			document.body.appendChild(target);

			var spinner = new Spinner(opts).spin(target);
			var overlay = fesSpinner({
				text: fes_form.loadingtext,
				spinner: spinner
			});
		}

		if (form_data) {
				submit_button.attr('disabled', 'disabled').addClass('button-primary-disabled');
				$.post(registration_button.ajax.url, form_data, function (response) {
					var title = '';
					var message = '';

					if (response.success) {
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
						var title = 'Could not connect';
						var message = $(xhr.responseponseText).text();
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
		rich_texts = [],
		val;

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