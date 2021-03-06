if(!jsSite) { var jsSite = new Object(); }

/**
 * Site related objects
 *
 * @author	Tijs Verkoyen <tijs@sumocoders.be>
 */
jsSite =
{
	// datamembers
	debug: false,
	current:
	{
		module: null,
		action: null,
		language: null
	},


	// init, something like a constructor
	init: function()
	{
		// get url and split into chunks
		var chunks = document.location.pathname.split('/');
		if(typeof chunks[1] == 'undefined') chunks[1] = 'nl';		// @todo	fix me
		if(typeof chunks[2] == 'undefined') chunks[2] = 'example';	// @todo	fix me
		if(typeof chunks[3] == 'undefined') chunks[3] = 'index';	// @todo	fix me

		// set some properties
		jsSite.current.module = chunks[2];
		jsSite.current.action = chunks[3];
		jsSite.current.language = chunks[1];

		// init stuff
		jsSite.initAjax();
		jsSite.controls.init();
		jsSite.forms.init();
		jsSite.layout();
		
		try
		{
			// build method
			var method = 'jsSite.'+ jsSite.current.module +'.init()';

			// try to call the method
			eval(method);
		}
		catch(e)
		{
			if(jsSite.debug) console.log(e);
		}
	},


	// init ajax
	initAjax: function()
	{
		// set defaults for AJAX
		$.ajaxSetup(
		{
			cache: false,
			type: 'POST',
			dataType: 'json',
			timeout: 5000
		});

		// global error handler
		$(document).ajaxError(function(event, XMLHttpRequest, ajaxOptions)
		{
			// 403 means we aren't authenticated anymore, so reload the page
			if(XMLHttpRequest.status == 403) window.location.reload();

			// check if a custom errorhandler is used
			if(typeof ajaxOptions.error == 'undefined')
			{
				// init var
				var textStatus = '{$errGeneralError}';

				// get real message
				if(typeof XMLHttpRequest.responseText != 'undefined') textStatus = $.parseJSON(XMLHttpRequest.responseText).message;

				// show message
				$('#generalError').html('<p>'+ textStatus + '</p>').slideDown();
			}
		});

		// spinner stuff
		$(document).ajaxStart(function() { $('#ajaxSpinner').show(); });
		$(document).ajaxStop(function() { $('#ajaxSpinner').hide(); });
	},
	
	layout: function() 
	{
		jsSite.multiTab();
		$(window).resize(jsSite.multiTab);
		
		jsSite.menu();
	},
	
	menu: function()
	{
		$active = $('#mobileNavigationActive');
		$inactive = $('#mobileNavigationInactive');
		$overlay = $('#overlay');

		$('#mobileNavigationInactive a').on('click', function(e)
		{
			e.preventDefault();
			
			// hide menu
			if($active.is(':visible'))
			{
				$active.hide();
				$inactive.removeClass('selected');
				$overlay.hide();
				$overlay.unbind('click');
			}
			
			// show menu
			else 
			{
				$active.show();
				$inactive.addClass('selected');
				$overlay.show().height($(document).height() - 44).width($(document).width());
				$overlay.on('click', function(e) {
					$active.hide();
					$inactive.removeClass('selected');
					$overlay.hide();
					$overlay.unbind('click');
				});
			}
		});
		
	},
	
	multiTab: function() 
	{
		var $multiTab = $('.multiTab');
		var $lis = $('li', $multiTab);
		$lis.width($multiTab.width() / $lis.length - 1);
	},

	// end
	eoo: true
}

/**
 * Forms
 *
 * @author	Tijs Verkoyen <tijs@sumocoders.be>
 */
jsSite.controls = {
	// init, something like a constructor
	init: function()
	{
		$('.confirm').on('click', function(e) {
			var message = $(this).data('message');
			return confirm(message);
		});
	},
	
	// end
	eoo: true
}


/**
 * Collection module js.
 *
 * @author Dieter Vanden Eynde <dieter@dieterve.be>
 */
jsSite.collections =
{
	init: function()
	{
		$('#name').autocomplete(
		{
			source: function(request, response)
			{
				$.ajax(
				{
					url:  '/ajax.php?module=collections&action=autocomplete&language=' + jsSite.current.language,
					data: { term: request.term },
					success: function(data, textStatus)
					{
						// init var
						var realData = [];

						// alert the user
						if(data.code != 200 && jsSite.debug)
						{
							alert(data.message);
						}

						if(data.code == 200)
						{
							for(var i in data.data)
							{
								realData.push(
								{
									label: data.data[i],
									value: data.data[i]
								});
							}
						}

						// set response
						response(realData);
					}
				});
			}
		});
	}
}


/**
 * Forms
 *
 * @author	Tijs Verkoyen <tijs@sumocoders.be>
 */
jsSite.facebook =
{
	status: '',
		
	// init, something like a constructor
	init: function()
	{
		FB.getLoginStatus(function(response) {
			jsSite.facebook.status = response.status;

			// subscribe to auth changes
			FB.Event.subscribe('auth.authResponseChange', function(response) 
			{
				if(response.status != jsSite.facebook.status && response.status != '') document.location.reload();
			});
		});
		
		FB.Event.subscribe('edge.create', function(response) 
		{
			if(typeof itemId != undefined) jsSite.items.updateLikes('up', itemId);
		});		

		FB.Event.subscribe('edge.remove', function(response) 
		{
			if(typeof itemId != undefined) jsSite.items.updateLikes('down', itemId);
		});
		
		$('input#publishToFacebook').on('change', function(e) {
			if($(this).is(':checked'))
			{
				FB.login(function(response) 
				{
					if(response.authResponse)
					{
						// juij, correct rights
					}
					else
					{
						$('input#publishToFacebook').removeAttr('checked');
					}
				}, {scope: 'publish_actions'});
			}
		});
	},

	// end
	eoo: true
}


/**
 * Forms
 *
 * @author	Tijs Verkoyen <tijs@sumocoders.be>
 */
jsSite.forms =
{
	// init, something like a constructor
	init: function()
	{
		jsSite.forms.placeholders();	// make sure this is done before focussing the first field
		jsSite.forms.datefields();
	},


	datefields: function() {
		$('.inputDate').each(function() {
			if($(this).attr('type') == 'text') {
				var maxDate = $(this).data('maxdate');

				$(this).datepicker({
					closeText: '{$lblClose|ucfirst}',
					dateFormat: 'dd/mm/yy',
					dayNames: [ '{$msgDatepickerFullSunday}', '{$msgDatepickerFullMonday}', '{$msgDatepickerFullTuesday}', '{$msgDatepickerFullWednesday}', '{$msgDatepickerFullThursday}', '{$msgDatepickerFullFriday}', '{$msgDatepickerFullSaterday}' ],
					dayNamesMin: [ '{$msgDatepickerMinimalSunday}', '{$msgDatepickerMinimalMonday}', '{$msgDatepickerMinimalTuesday}', '{$msgDatepickerMinimalWednesday}', '{$msgDatepickerMinimalThursday}', '{$msgDatepickerMinimalFriday}', '{$msgDatepickerMinimalSaterday}' ],
					dayNamesShort: [ '{$msgDatepickerShortSunday}', '{$msgDatepickerShortMonday}', '{$msgDatepickerShortTuesday}', '{$msgDatepickerShortWednesday}', '{$msgDatepickerShortThursday}', '{$msgDatepickerShortFriday}', '{$msgDatepickerShortSaterday}' ],
					firstday: 1,
					monthNames: [ '{$msgDatepickerFullJanuary}', '{$msgDatepickerFullFebruary}', '{$msgDatepickerFullMarch}', '{$msgDatepickerFullApril}', '{$msgDatepickerFullMay}', '{$msgDatepickerFullJune}', '{$msgDatepickerFullJuly}', '{$msgDatepickerFullAugust}', '{$msgDatepickerFullSeptember}', '{$msgDatepickerFullOctober}', '{$msgDatepickerFullNovember}', '{$msgDatepickerFullDecember}' ],
					monthNamesShort: [ '{$msgDatepickerShortJanuary}', '{$msgDatepickerShortFebruary}', '{$msgDatepickerShortMarch}', '{$msgDatepickerShortApril}', '{$msgDatepickerShortMay}', '{$msgDatepickerShortJune}', '{$msgDatepickerShortJuly}', '{$msgDatepickerShortAugust}', '{$msgDatepickerShortSeptember}', '{$msgDatepickerShortOctober}', '{$msgDatepickerShortNovember}', '{$msgDatepickerShortDecember}' ],
					nextText: '{$lblNext}',
					prevText: '{$lblPrevious}',
					maxDate: maxDate
				});
			}
		});
	},


	// set placeholders
	placeholders: function()
	{
		// detect if placeholder-attribute is supported
		jQuery.support.placeholder = ('placeholder' in document.createElement('input'));

		if(!jQuery.support.placeholder)
		{
			// bind focus
			$('input[placeholder]').focus(function()
			{
				// grab element
				var input = $(this);

				// only do something when the current value and the placeholder are the same
				if(input.val() == input.attr('placeholder'))
				{
					// clear
					input.val('');

					// remove class
					input.removeClass('placeholder');
				}
			});

			$('input[placeholder]').blur(function()
			{
				// grab element
				var input = $(this);

				// only do something when the input is empty or the value is the same as the placeholder
				if(input.val() == '' || input.val() == input.attr('placeholder'))
				{
					// set placeholder
					input.val(input.attr('placeholder'));

					// add class
					input.addClass('placeholder');
				}
			});

			// call blur to initialize
			$('input[placeholder]').blur();

			// hijack the form so placeholders aren't submitted as values
			$('input[placeholder]').parents('form').submit(function()
			{
				// find elements with placeholders
				$(this).find('input[placeholder]').each(function()
				{
					// grab element
					var input = $(this);

					// if the value and the placeholder are the same reset the value
					if(input.val() == input.attr('placeholder')) input.val('');
				});
			});
		}
	},

	// end
	eoo: true
}

jsSite.items = 
{
	init: function()
	{
		if($('#preloadImage').length > 0) jsSite.items.findImage();
		$('#addCustom').on('click', jsSite.items.addCustomField);
		$(document).on('click', '.deleteCustom', jsSite.items.deleteCustomField);
	},

	/**
	 * Add custom field inputs
	 */
	addCustomField: function(e)
	{
		e.preventDefault();
		html = '<div class="oneLiner"><p><input class="customKey inputText smallInput" type="text" name="names[]" placeholder="Custom Field Name" /></p><p><input class="customValue inputText smallInput" type="text" name="values[]" placeholder="Custom Field Value"/></p><p><a href="#" class="deleteCustom" style="font-size: 11px; line-height: 22px;">Delete</a></p></div>';
		$(this).before(html);
	},

	/**
	 * Delete custom field
	 */
	deleteCustomField: function(e)
	{
		e.preventDefault();
		$(this).parent().parent().remove();
	},

	/**
	 * Fetch item images from other sources
	 */
	findImage: function()
	{
		var chunks = document.location.pathname.split('/');
		var $inputs = $('form').parent().find('input[type=text]');
		$inputs.blur(function()
		{
			/*
			 * Build search keyword out of
			 * - collection name
			 * - item name
			 * - custom fields
			 */
			var keyword = new Array();
			keyword.push(chunks[5]);
			$inputs.each(function()
			{
				keyword.push($(this).val());
			});

			var options =
			{
				v: '1.0',
				q: keyword.join(' '),
//				as_rights: 'cc_publicdomain', // @todo: once we go public, enable this (and connect to other sites like wiki commons, in hopes of finding more results) - for dev purposes, leave this on: more results
				rsz: 1,
				callback: 'jsSite.items.processImage'
			};
			var options = $.param(options);
			$.getScript('https://ajax.googleapis.com/ajax/services/search/images?' + options);
		});
	},

	/**
	 * Process the Google Images API result
	 */
	processImage: function(json)
	{
		if(json.responseData.results[0].url)
		{
			$('img#preload-image-preview').show().attr('src', json.responseData.results[0].url);
			$('div#preload-image-preview').show().css('background-image', 'url(' + json.responseData.results[0].url + ')');
			$('#preloadImage').val(json.responseData.results[0].url);
		}
	},

	updateLikes: function(direction, id)
	{
		$.ajax(
		{
			url:  '/ajax.php?module=items&action=update_likes&language=' + jsSite.current.language,
			data: { id: id, direction: direction },
			success: function(data, textStatus)
			{
				// alert the user
				if(data.code != 200 && jsSite.debug)
				{
					alert(data.message);
				}
			}
		});
	},
	
	// end
	eoo: true
}


$(document).ready(jsSite.init);