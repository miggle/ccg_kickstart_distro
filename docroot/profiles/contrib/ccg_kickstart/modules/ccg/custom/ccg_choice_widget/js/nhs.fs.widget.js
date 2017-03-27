/********************************************************************
*	Author: 		Jim English										*	
*	Company: 		JRE Solutions Ltd								*	
*	Description: 	A find services widget for built for the NHS.	*
********************************************************************/
var fsNHSDefaults = {
	pageSize: 4,													// maximum number of results returned for each call
	serviceUrl: 'http://v1.syndication.nhschoices.nhs.uk/',			// NHS service URL
	apiKey: 'PHRJCDTY',												// NHS API key
	maxServiceTabs: 3,												// maximum number of tabs that can be initialised
	serviceMappings: {												// service mappings to be appended to serviceURL to create service path
		'1': 'organisations/gppractices',							// gp practices service path
		'2': 'organisations/dentists',								// dentist service path
		'3': 'organisations/pharmacies',							// pharmacies service path
		'4': 'organisations/hospitals'								// hospital service path
	},
	content: {														// content object that contains all widget configurable content
		logoURL: 'http://media.nhschoices.nhs.uk/nhs/nhs.fs.widget/logo-nhs_choices.gif',							// NHS choices URL path
		logoAlt: 'NHS Choices',										// NHS choices alternative text for logo
		subtitle: 'Find and choose services',						// widget sub title
		findBtn: 'Find',											// find button text
		resetBtn: 'Reset',											// reset button text
		searchText: 'postcode',										// text if search filed left blank (user tip)
		telephoneText: 'Telephone',									// telephone label in search result entry
		noResults: 'Your search for %value% returned no results.',	// no results returned text - Note that this needs to have the text '%value%' in which gets replcaed with the search the user entered
		serviceTypes: {												// service mapping to tab titles
			'1': 'GP',												// gp practices tab title
			'2': 'Dentist',											// dentist tab title
			'3': 'Pharmacy',										// pharmacies tab title
			'4': 'Hospital'											// hospital tab title
		},
		aboutTab: 'About',											// about tab title
		about: '<p>NHS Choices widget, powered by the <a href="http://www.nhs.uk/servicedirectories/Pages/ServiceSearch.aspx" TARGET="_blank"> NHS Choices Services Finder</a>.</p><p>For further information, see <a href="http://www.nhs.uk/aboutNHSChoices/professionals/syndication/Pages/Webservices.aspx" TARGET="_blank"> NHS Choices syndication pages</a>.</p>'						// about tab content (can be HTML or just an unformatted text string)
	}
};
// widget code below
(function ($, window, document, undefined) {
	$.fsNHSWidget = function (el, options) {
		var widgets = $.fsNHSWidget.widgets++,
			$el = $(el),
			$navLnks,
			$widget,
			$searchFields,
			defaults = $.fsNHSWidget.defaults;
		
		// deal with different initialisation options
		if (typeof options === 'string') {
			options = {
				types: options.split(',')	
			};
		} else if (typeof options === 'object') {
			if ($.isArray(options)) {
				options = {
					types: options
				};
			}
			if (typeof options.types === 'string') {
				options.types = options.types.split(',');
			}
		} else {
			return;	
		}
		// extend options
		options = $.extend(true, {}, defaults, options);
		options.types = !options.types || !$.isArray(options.types) ? [] : options.types;

		//$('head').append('<link rel="stylesheet" type="text/css" media="all" href="' + options.cssURL + '"/>');
		
		// create HTML function
		function init () {
			var html = '<div id="fs-nhs-widget" class="fs-nhs-widget"><div class="nhs-widget-inner"><img src="' + options.content.logoURL + '" alt="' + options.content.logoURL + '"/><p class="nhs-widget-title"><strong>' + options.content.subtitle + '</strong></p><ul class="nhs-widget-nav nhs-widget-clear">',
				li = '',
				tabs = [],
				exists = [];
			$.each(options.types, function (i, val) {
				var t = options.content.serviceTypes[val];
				
				if (t && !!$.inArray(val, exists)) {
					li += '<li class="' + ((i === 0) ? 'nhs-widget-first nhs-widget-active' : '') + '"><a href="#nhs-widget-' + i + '-' + widgets + '">' + t + '</a></li>';
					tabs.push('<div id="nhs-widget-' + i + '-' + widgets + '" class="nhs-widget-tab" ' + ((i === 0) ? '' : 'style="display:none;"') + '><div class="nhs-widget-clear nhs-widget-overflow"><form action="#' + val + '"><div><input class="nhs-widget-input" type="text" title="postcode" maxlength="8" /></div><div class="nhs-widget-btn-wrapper"><button class="nhs-widget-find-js">' + options.content.findBtn + '</button></div><div class="nhs-widget-btn-wrapper"><button class="nhs-widget-reset-js">' + options.content.resetBtn + '</button></div></form></div><div class="nhs-widget-clear"></div><div class="nhs-widget-search-results"><ul></ul></div></div>');
					exists.push(val);
				}
				return (i < (options.maxServiceTabs - 1));
			});
			html += li + '<li class="' + ((!options.types.length) ? 'nhs-widget-first nhs-widget-active' : '') + '"><a href="#nhs-widget-about-' + widgets + '">' + options.content.aboutTab + '</a></li></ul><div class="nhs-widget-tabs nhs-widget-clear">' + tabs.join('') + '<div id="nhs-widget-about-' + widgets + '" class="nhs-widget-tab" style="' + ((!options.types.length) ? '' : 'display:none;') + '">' + options.content.about + '</div></div></div>';
			$(el).append(html);
			
			$widget = $el.find('.fs-nhs-widget');
			$searchFields = $widget.find('.nhs-widget-input');
			$navLnks = $widget.find('.nhs-widget-nav a');	
			
			// title text swap for inputs function
			$widget.find('input[type=text]').each(function () {
				this.value = this.title;									 
			}).focus(function () {
				if (this.value === this.title) {
					this.value = '';
				}
			}).blur(function () {
				if (this.value === '') {
					this.value = this.title;
				}									  
			});
			
			// tab function
			$navLnks.click(function () {
				var currentInput = $searchFields.filter(':visible');
				$navLnks.parent('li').removeClass('nhs-widget-active');
				$(this).parent('li').addClass('nhs-widget-active');
				$widget.find('.nhs-widget-tab').hide();
				$(this.href.substring(this.href.indexOf('#'))).show();
				if (currentInput.length) {
					$searchFields.val(currentInput.val());
				}
				this.blur();
				return false;
			});
			// reset button function
			$widget.find('.nhs-widget-reset-js').click(function () {
				var $tab = $(this).parents('.nhs-widget-tab');
				$tab.find('.nhs-widget-search-results ul').empty();
				$tab.find('.nhs-widget-input').val('').focus();
				return false;
			});
			// find button function
			$widget.find('.nhs-widget-find-js').click(function () {
				var $btns = $widget.find('.nhs-widget-find-js, .nhs-widget-reset-js'),
					$tab = $(this).parents('.nhs-widget-tab'),
					$form = $tab.find('form'),
					$input = $form.find('.nhs-widget-input'),
					$ul = $tab.find('.nhs-widget-search-results ul'),
					value = $input.val(),
					frmAction = $form.attr('action'),
					type = options.serviceMappings[frmAction.substring(frmAction.indexOf('#') + 1)],
					prot = 'http' + (/^https/.test(window.location.protocol)?'s':'');
	
				if (value !== $input.attr('title') && value !== '') {
					$ul.empty();
					$btns.attr('disabled', 'disabled');
					$tab.addClass('nhs-widget-loader');
					// get JSON
					$.getJSON(prot + "://query.yahooapis.com/v1/public/yql?callback=?",
						{
							q: "select * from xml where url='" + options.serviceUrl + type + "/postcode/" + value.replace(/\s/g, "") + ".xml?apikey=" + options.apiKey + "'",
							diagnostics: true,
							format: "json"
						},
						function (json) {
							var service = (json && json.query && Number(json.query.count) !== 0 && json.query.results && json.query.results.feed && json.query.results.feed.entry) ? json.query.results.feed.entry : null,
								tmpObj,
								tmpAddrsObj,
								tmpAddrs,
								i = 0,
								sLen,
								j,
								lis = [];	
							$tab.removeClass('nhs-widget-loader');
							if (service) {
								sLen = service.length;
								// loop and create LIs to append
								for(j = 0; j < sLen; j++) {
									if (j <= options.pageSize) {
											if (service[j]) {
												tmpObj = service[j].content.organisationSummary;
												tmpAddrsObj = tmpObj.address;
												tmpAddrs = (tmpAddrsObj.addressLine[0] && tmpAddrsObj.addressLine[0] + ', ' || '') + (tmpAddrsObj.addressLine[1] && tmpAddrsObj.addressLine[1] + ', ' || '') + (tmpAddrsObj.addressLine[2] && tmpAddrsObj.addressLine[2] + ', ' || '') + (tmpAddrsObj.addressLine[3] && tmpAddrsObj.addressLine[3] + ', ' || '') + (tmpAddrsObj.addressLine[4] && tmpAddrsObj.addressLine[4] + ', ' || '') + (tmpAddrsObj.postcode || '');
												lis.push('<li><span class="nhs-widget-addr"><a href="' + service[j].link[1].href + '" target="_blank"><strong>' + tmpObj.name + '</strong></a></span><span class="nhs-widget-addr">' + tmpAddrs + '</span> <span>' + options.content.telephoneText + ': ' + tmpObj.contact.telephone + '</span></li>');
												i++;
										}
									} else {
										break;
									}
								}
								// append LIs
								$ul.append(lis.join(''));
							} else {
								$ul.append('<li class="nhs-widget-error">' + options.content.noResults.replace('%value%', "'" + value + "'") + '</li>');
							}
							$btns.removeAttr('disabled');
						}
					);
					
				}
				
				this.blur();
				return false;
			});
		}
		// initialise
		init();
	};
	// set defaults and counter
	$.fsNHSWidget.widgets = 0;  
	$.fsNHSWidget.defaults = fsNHSDefaults;
	
	// extend jQuery object
	$.fn.extend({
		fsNHSWidget: function (options) {
			options = options || {};
			this.each(function () {
				var instance = $.data(this, 'fsNHSWidget');
				if (!instance) {
					$.data(this, 'fsNHSWidget', new $.fsNHSWidget(this, options));
				}
			});
			return this;
		}
	});
}(jQuery, window, document));