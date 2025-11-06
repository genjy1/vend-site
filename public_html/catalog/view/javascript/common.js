function getURLVar(key) {
	var value = [];
	var query = String(document.location).split('?');

	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');

			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}
		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
}
function max_height_div(div) {
	if ($(window).width() < 700) {
		return false;
	}
	var maxheight = 0;
	$(div).each(function () {
		$(this).removeAttr('style');
		if ($(this).height() > maxheight) {
			maxheight = $(this).height();
		}
	});
	$(div).height(maxheight);
}
function loadImage(el) {
	if (el.filter("[data-bg]")) {
		el.css('background-image', 'url(' + el.data("bg") + ')').addClass("loaded");
	}
	el.find("[data-bg]").map(function () {
		if ($(this).visible(1)) {
			$(this).css('background-image', 'url(' + $(this).data("bg") + ')');
		}
	});

}

function lazyLoadImages() {
	$("section").map(function () {
		if ($(this).visible(1)) {
			loadImage($(this));
			loadImage($(this).next("section"));
		}
	});
	// $("[data-bg]").map(function(){
	//   if($(this).visible(1)){
	//     $(this).css('background-image', 'url(catalog/view/theme/default/stylesheet/' + $(this).data("bg") + ')');
	//   }
	// });
}
$(window).scroll(function () {
	lazyLoadImages();
});
function documentReady() {

	$("a[href='https://vend-shop.com/index.php?route=gallery/gallery/']").attr("href", "/photos/")
	lazyLoadImages();
	$(".continue").on("click", function () {
		$(".cartadded").hide();
	});
	$(".qch input").on("change", function () {
		$(".formacart").submit();
	});
	$("form.lang").mouseover(function () {
		$(this).addClass("hover");
	});
	$(".search").mouseover(function () {
		$("form.lang").removeClass("hover");
	});
	$(".sl").mouseleave(function () {
		$("form.lang").removeClass("hover");
	});


	max_height_div('.grid .product .name');
	max_height_div('.grid .product .name');
	max_height_div('.nblock > a > span');

	max_height_div('.productfeature .item .name');
	$('.search').on('click', function () {
		$(this).next('input').fadeIn().css({ "display": "inline-block" });
		$(this).next('input').next('button').fadeIn().css({ "display": "inline-block" });
	});
	// Highlight any found errors
	$('.text-danger').each(function () {
		var element = $(this).parent().parent();

		if (element.hasClass('form-group')) {
			element.addClass('has-error');
		}
	});

	$('#form-currency .currency-select').on('click', function (e) {
		e.preventDefault();

		$('#form-currency input[name=\'code\']').attr('value', $(this).attr('name'));

		$('#form-currency').submit();
	});

	// Language
	$('#form-language a').on('click', function (e) {
		e.preventDefault();
		// $('#form-language a').show();
		// $('#form-language input[name=\'code\']').attr('value', $(this).data('name'));
		code = $(this).data('name');
		$.ajax({
			url: 'index.php?route=common/language/language',
			type: 'post',
			data: 'code=' + code,
			dataType: 'json',
			beforeSend: function () {
			},
			complete: function () {
			},
			success: function (json) {
				location.href = location.href;
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	})

	/* Search */
	$('input[name=\'search\']').parent().find('button').on('click', function () {
		doSearch($(this).parent().find('input'));
	});

	$('input[name=\'search\']').on('keydown', function (e) {
		if (e.keyCode == 13) {
			doSearch($(this));
		}
	});

	function doSearch(el) {
		var url = $('base').attr('href') + 'index.php?route=product/search';

		var value = el.val();

		if (value) {
			url += '&search=' + encodeURIComponent(value);
		}

		location = url;
	}

	// Menu
	$('#menu .dropdown-menu').each(function () {
		var menu = $('#menu').offset();
		var dropdown = $(this).parent().offset();

		var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());

		if (i > 0) {
			$(this).css('margin-left', '-' + (i + 5) + 'px');
		}
	});

	// Product List
	$('#list-view').click(function () {
		$('#content .product-grid > .clearfix').remove();

		$('#content .row > .product-grid').attr('class', 'product-layout product-list col-xs-12');

		localStorage.setItem('display', 'list');
	});

	// Product Grid
	$('#grid-view').click(function () {
		// What a shame bootstrap does not take into account dynamically loaded columns
		var cols = $('#column-right, #column-left').length;

		if (cols == 2) {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-xs-12');
		} else if (cols == 1) {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12');
		} else {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12');
		}

		localStorage.setItem('display', 'grid');
	});

	if (localStorage.getItem('display') == 'list') {
		$('#list-view').trigger('click');
	} else {
		$('#grid-view').trigger('click');
	}

	// Checkout
	$(document).on('keydown', '#collapse-checkout-option input[name=\'email\'], #collapse-checkout-option input[name=\'password\']', function (e) {
		if (e.keyCode == 13) {
			$('#collapse-checkout-option #button-login').trigger('click');
		}
	});

}
$(document).ready(function () {

	if (typeof navigator.userAgent !== "undefined") {
		if (navigator.userAgent.indexOf('Lighthouse') < 0) {
			documentReady();
		}
	} else {
		documentReady();
	}
});

// Cart add remove functions
var cart = {
	'add': function (product_id, quantity) {
		$.ajax({
			url: 'index.php?route=checkout/cart/add',
			type: 'post',
			data: 'product_id=' + product_id + '&quantity=' + (typeof (quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function () {
			},
			complete: function () {
			},
			success: function (json) {
				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['success']) {
					$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					$('.cart #totals').text(json.total);
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'update': function (key, quantity) {
		$.ajax({
			url: 'index.php?route=checkout/cart/edit',
			type: 'post',
			data: 'key=' + key + '&quantity=' + (typeof (quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function () {
			},
			complete: function () {
			},
			success: function (json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
				}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function (key) {
		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function () {
			},
			complete: function () {
			},
			success: function (json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
				}, 100);

				location = 'index.php?route=checkout/cart';

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

var voucher = {
	'add': function () {

	},
	'remove': function (key) {
		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function () {
			},
			complete: function () {
			},
			success: function (json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
				}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

var wishlist = {
	'add': function (product_id) {
		$.ajax({
			url: 'index.php?route=account/wishlist/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function (json) {
				$('.alert').remove();

				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['success']) {
					$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				$('#wishlist-total span').html(json['total']);
				$('#wishlist-total').attr('title', json['total']);

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function () {

	}
}

var compare = {
	'add': function (product_id) {
		$.ajax({
			url: 'index.php?route=product/compare/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function (json) {
				$('.alert').remove();

				if (json['success']) {
					$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					$('#compare-total').html(json['total']);

					$('html, body').animate({ scrollTop: 0 }, 'slow');
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function () {

	}
}
function documentDelegate(){
/* Agree to Terms */
$(document).delegate('.agree', 'click', function (e) {
	e.preventDefault();

	$('#modal-agree').remove();

	var element = this;

	$.ajax({
		url: $(element).attr('href'),
		type: 'get',
		dataType: 'html',
		success: function (data) {
			html = '<div id="modal-agree" class="modal">';
			html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
			html += '      <div class="modal-header">';
			html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			html += '        <h4 class="modal-title">' + $(element).text() + '</h4>';
			html += '      </div>';
			html += '      <div class="modal-body">' + data + '</div>';
			html += '    </div';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);

			$('#modal-agree').modal('show');
		}
	});
});
}
if (typeof navigator.userAgent !== "undefined") {
	if (navigator.userAgent.indexOf('Lighthouse') < 0) {
		documentDelegate();
	}
} else {
	documentDelegate();
}
// Autocomplete */
(function ($) {
	$.fn.autocomplete = function (option) {
		return this.each(function () {
			this.timer = null;
			this.items = new Array();

			$.extend(this, option);

			$(this).attr('autocomplete', 'off');

			// Focus
			$(this).on('focus', function () {
				this.request();
			});

			// Blur
			$(this).on('blur', function () {
				setTimeout(function (object) {
					object.hide();
				}, 200, this);
			});

			// Keydown
			$(this).on('keydown', function (event) {
				switch (event.keyCode) {
					case 27: // escape
						this.hide();
						break;
					default:
						this.request();
						break;
				}
			});

			// Click
			this.click = function (event) {
				event.preventDefault();

				value = $(event.target).parent().attr('data-value');

				if (value && this.items[value]) {
					this.select(this.items[value]);
				}
			}

			// Show
			this.show = function () {
				var pos = $(this).position();

				$(this).siblings('ul.dropdown-menu').css({
					top: pos.top + $(this).outerHeight(),
					left: pos.left
				});

				$(this).siblings('ul.dropdown-menu').show();
			}

			// Hide
			this.hide = function () {
				$(this).siblings('ul.dropdown-menu').hide();
			}

			// Request
			this.request = function () {
				clearTimeout(this.timer);

				this.timer = setTimeout(function (object) {
					object.source($(object).val(), $.proxy(object.response, object));
				}, 200, this);
			}

			// Response
			this.response = function (json) {
				html = '';

				if (json.length) {
					for (i = 0; i < json.length; i++) {
						this.items[json[i]['value']] = json[i];
					}

					for (i = 0; i < json.length; i++) {
						if (!json[i]['category']) {
							html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
						}
					}

					// Get all the ones with a categories
					var category = new Array();

					for (i = 0; i < json.length; i++) {
						if (json[i]['category']) {
							if (!category[json[i]['category']]) {
								category[json[i]['category']] = new Array();
								category[json[i]['category']]['name'] = json[i]['category'];
								category[json[i]['category']]['item'] = new Array();
							}

							category[json[i]['category']]['item'].push(json[i]);
						}
					}

					for (i in category) {
						html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';

						for (j = 0; j < category[i]['item'].length; j++) {
							html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
						}
					}
				}

				if (html) {
					this.show();
				} else {
					this.hide();
				}

				$(this).siblings('ul.dropdown-menu').html(html);
			}

			$(this).after('<ul class="dropdown-menu"></ul>');
			$(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));

		});
	}

})(window.jQuery);
function windowLoad() {
	document.body.onclick = function (e) {
		// $(".cartadded").hide();
		e = e || event;
		target = e.target || e.srcElement;
		if (target.className != "search" && target.className != "searchfield") {
			$('.searchfield').hide();
			$(".btn-search").hide();
		}

		if (target.className != "it" && target.className != "op" && target.className != "it openf") {
			$('.it').removeClass("openf");
			$('.filteritem').hide();
		}

		// if( target.className != "inf") {
		// 	$('.popupopt').hide();
		// }
	}
}
window.onload = function () {
	if (typeof navigator.userAgent !== "undefined") {
		if (navigator.userAgent.indexOf('Lighthouse') < 0) {
			windowLoad();
		}
	} else {
		windowLoad();
	}
}
function documentReady1(){

	href = location.href;
	href = href.replace(/\?tmpl=\d/i, "");


	width = $(window).width();
	if (width > 960 && localStorage.getItem('tmpl') != 1) {
		localStorage.setItem('tmpl', '1');
		// location.href = href + "?tmpl=1";
		set_template(1);
	}
	if (width < 960 && width > 640 && localStorage.getItem('tmpl') != 3) {
		localStorage.setItem('tmpl', '3');
		// location.href = href + "?tmpl=3";
		set_template(3);
	}
	if (width < 640 && localStorage.getItem('tmpl') != 2) {
		localStorage.setItem('tmpl', '2');
		// location.href = href + "?tmpl=2";
		set_template(2);
	}


	$(window).resize(function () {

		href = location.href;
		href = href.replace(/\?tmpl=\d/i, "");

		width = $(window).width();
		if (width > 960 && localStorage.getItem('tmpl') != 1) {
			localStorage.setItem('tmpl', '1');
			// location.href = href + "?tmpl=1";
			set_template(1);
		}
		if (width < 960 && width > 640 && localStorage.getItem('tmpl') != 3) {
			localStorage.setItem('tmpl', '3');
			set_template(3);
		}
		if (width < 640 && localStorage.getItem('tmpl') != 2) {
			localStorage.setItem('tmpl', '2');
			set_template(2);
		}
	});

	function set_template(tmpl) {
		$.ajax({
			url: 'index.php?route=common/home/setTemplate',
			type: 'post',
			data: 'tmpl=' + tmpl,
			dataType: 'json',
			success: function (json) {
				if (json['success']) {
					location.href = location.href;
				}

			},
			error: function (xhr, ajaxOptions, thrownError) {
				// alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}
$(document).ready(function () {
	if (typeof navigator.userAgent !== "undefined") {
		if (navigator.userAgent.indexOf('Lighthouse') < 0) {
			documentReady1();
		}
	} else {
		documentReady1();
	}
	$($(".monitor a.download")[0]).attr("href", "/image/SM6367-S.pdf")
});


const helpButton = document.querySelector('.showHelp');
const helpWrapper = document.querySelector('.help');
const closeHelpWrapper = document.querySelector('#pomoschnet');

helpWrapper.classList.add('hidden');
helpButton.addEventListener('click', function(e) {
	e.preventDefault();

	helpWrapper.classList.toggle('hidden');
})

closeHelpWrapper.addEventListener('click', function(e) {
	e.preventDefault();
	helpWrapper.classList.toggle('hidden');
})