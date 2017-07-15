String.prototype.formatUrl = function(tokens) {
	var str = this;
	var formatted = str.replace(/\{([A-Za-z0-9.]+)\}/g, function(match, idx) {
		var value = idx.split('.').reduce(function(acc, cur) {
		if (acc === false) return acc;
		else if (typeof acc === 'object' && acc.hasOwnProperty(cur)) return acc[cur];
		else return false;
		}, tokens);

		return (value === false) ? match : value;
	});

	return formatted;
};

(function($){var a={},c="doTimeout",d=Array.prototype.slice;$[c]=function(){return b.apply(window,[0].concat(d.call(arguments)))};$.fn[c]=function(){var f=d.call(arguments),e=b.apply(this,[c+f[0]].concat(f));return typeof f[0]==="number"||typeof f[1]==="number"?this:e};function b(l){var m=this,h,k={},g=l?$.fn:$,n=arguments,i=4,f=n[1],j=n[2],p=n[3];if(typeof f!=="string"){i--;f=l=0;j=n[1];p=n[2]}if(l){h=m.eq(0);h.data(l,k=h.data(l)||{})}else{if(f){k=a[f]||(a[f]={})}}k.id&&clearTimeout(k.id);delete k.id;function e(){if(l){h.removeData(l)}else{if(f){delete a[f]}}}function o(){k.id=setTimeout(function(){k.fn()},j)}if(p){k.fn=function(q){if(typeof p==="string"){p=g[p]}p.apply(m,d.call(n,i))===true&&!q?o():e()};o()}else{if(k.fn){j===undefined?e():k.fn(j===false);return true}else{e()}}}})(jQuery);

$(function() {

	/**
	* Detect hash code inside URL and open modal
	* We avoid to match facebook hash : '#_=_'
	*/
	if ($(location)[0].hash && $(location)[0].hash !== '#_=_' && $($(location)[0].hash + '.modal')) {
		$($(location)[0].hash + '.modal').modal('show');
	}

	$('form.ajax').formajax({
		done : function(data, textStatus, response) {
			$('.information-offer').removeClass('squeezed-regie squeezed-preinscription');
			$('#SuccessModal').modal('show');

			if ($(this.form).data('formajax-success')) {
				var success = $(this.form).data('formajax-success');

				if (/redirect\(.*\)/.test(success)) {
					var url = decodeURI(success.match(/redirect\((.*)\)/)[1]).formatUrl(data);

					window.location.href = url;
				}
				else if (/reload/.test(success)) {
					window.location.reload();
				}
				else if (/closemodal/.test(success)) {
					$('.modal.in').modal('hide');
				}
				else if (/openmodal\(.*\)/.test(success)) {
					$('.modal.in').modal('hide');
					var modal = success.match(/openmodal\((.*)\)/)[1];
					$(modal).modal('show');
				} else if (/multiStepGoTo\(.*\)/.test(success)) {
					var step = success.match(/multiStepGoTo\((.*)\)/)[1];
					$('.modal.in').trigger('multiStepGoTo', [step]);
				}
			}
		},
		before : function() {
			var deferred = $.Deferred();
			var _form = this;

			// Reinitialize form inputs
			$(this.settings.error, this.form).prop('hidden', 'hidden').empty();

			$('.form-group.has-error', this.form).removeClass('has-error');

			$(this.settings.button, this.form).button('loading');

			if($(this.form).hasClass('stripe-form')) {

				var stripeResponseHandler = function(status, response) {
					if (response.error) {
						var stripeErrorMessages = {
							incorrect_number: "Le code de la carte est incorrect.",
							invalid_number: "Le code de la carte n'est pas valide.",
							invalid_expiry_month: "Le mois d'expiration de la carte n'est pas valide.",
							invalid_expiry_year: "l'année d'expiration de la carte n'est pas valide.",
							invalid_cvc: "le CVC de la carte n'est pas valide.",
							expired_card: "La carte est expirée.",
							incorrect_cvc: "Le CVC de la carte est incorrect.",
							card_declined: "La carte a été refusée.",
							processing_error: "Une erreur s'est produite.",
							rate_limit:  "Une erreur s'est produite."
						};

						var html = _form.settings.formatErrors({"stripe":stripeErrorMessages[response.error.code]});

						$(_form.settings.error, _form.form).append(html).prop('hidden', false);
						deferred.reject();
					} else {
						$(_form.form).append($('<input type="hidden" name="stripe_token" />').val(response.id));
						deferred.resolve();
					}

				};

				Stripe.card.createToken($(this.form), stripeResponseHandler);

				return deferred;

			}
		},
		always: function() {
			$(this.form).find('input[name=stripe_token]').remove();
		},
		http: {
			_422: function(response) {
				var errors = response.responseJSON;
				var html = this.settings.formatErrors.call(this, errors);

				$(this.settings.error, this.form).append(html).prop('hidden', false);
			},
		},
		error : '.alert.alert-warning',
		button : 'button[type=submit]',
	});

	var buyer_type = $('.buyer_account_type');
	var render_account_form = function(buyer_type) {
		if (buyer_type == 'agency') {
			$('.buyer_activity').hide();
			$('.buyer_customers').show();
		} else {
			$('.buyer_activity').show();
			$('.buyer_customers').hide();
		}
	};

	render_account_form(buyer_type.val());
	buyer_type.change(function () {
		render_account_form(buyer_type.val());
	});

	$('.input-credit-card').on('focusin', function(){
		$('.input-credit-card').each(function() {
			$(this).attr('data-placeholder', $(this).attr('placeholder'));
			$(this).removeAttr('placeholder');
		});
	});
	$('.input-credit-card').on('focusout', function(){
		var empty = true;
		$('.input-credit-card').each(function() {
			if($(this).val()) {
				empty = false;
				return false;
			}
		});
		if(empty) {
			$('.input-credit-card').each(function() {
				$(this).attr('placeholder', $(this).attr('data-placeholder'));
				$(this).removeAttr('data-placeholder');
			});
		}
	});

	$('[data-toggle="tooltip"]').tooltip({
		html: true
	});

	$('.expand-card').on('click', function(){
		console.log($(this).parent());
		$(this).parent().toggleClass('container-dropdown-mobile--open');
		$(this).find('.icon').toggleClass('icon-simply-down').toggleClass('icon-simply-up');
	});

	//The FAQ toggle mechanics
	if ($(window).width() < 768) {
		toggleAndUntoggle();
	}
	else {
		toUntoggle();
	}
	$( window ).resize(function() {
		if ($(window).width() < 768) {
			toggleAndUntoggle();
		}
		else {
			toUntoggle();
		}
	});

	function toggleAndUntoggle () {
		$('.title-toggler').each(function(){
			$(this).removeClass('open');
		});
		$('.toggle').hide();
		$('.title-toggler').unbind('click').click(function(){
			if ($(this).hasClass('open')) {
				$(this).removeClass('open');
				$(this).children().eq(1).hide();
				$(this).next().hide();
			}
			else {
				$(this).addClass('open');
				$(this).children().eq(1).show();
				$(this).next().show();
			}
		});
	}

	function toUntoggle () {
		$('.toggle').show();
		$('.title-toggler').unbind('click');
		$('.toggler-arrow').hide();
	}

	//Affix
	$('#faqAffix').affix({
		offset: {
			top: 0,
			bottom: function () {
				return (this.bottom = $('footer').outerHeight(true)+50);
			}
		}
	});

	// Smooth Scroll
	$('a[href*=#]:not([href=#] , [data-toggle], [href=#modalContact])').click(function() {
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			if (target.length) {
				var margin = 85;
				if ($(window).width() < 768 || ($( window ).resize() && $(window).width() < 768)) {
					margin = 60;
				}
				$('html,body').animate({
					scrollTop: target.offset().top-margin
				}, 1000);
				return false;
			}
		}
	});

	//Body scrollspy
	var paddingScroll = 85;
	$('body').scrollspy({
   		offset: paddingScroll
   	});

	//Page search scroll
	var $location = window.location;

	if($location.pathname === '/ad_placement' && $location.search.length > 0) {
		var margin = 85;

		if ($(window).width() < 768 || ($( window ).resize() && $(window).width() < 768)) {
			margin = 60;
		}

		$('html,body').animate({
			scrollTop: $('#searchResults').offset().top - margin
		}, 1000);
	}

   	//Active on search inputs
   	$(".search-filter-grid .search-filter-grid_row_select .form-control").change(function() {
   		if($(this).val() !== null) {
   			$(this).addClass('hasChanged');
   		}
   	});

   	$(".search-filter-grid .search-filter-grid_row_select .form-control").each(function() {
   		if($(this).val() !== "all" && $(this).val() !== null) {
   			$(this).addClass('hasChanged');
   		}
   	});

   	//Lazy load
   	$('.lazy').lazyload({
		effect: 'fadeIn',
		threshold: 600,
	});

	$(".multiple-select").select2();
});

