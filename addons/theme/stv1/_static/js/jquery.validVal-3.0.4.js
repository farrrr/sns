/*	 *	jQuery validVal version 3.0.4 *	demo's and documentation: *	validval.frebsite.nl * *	Copyright (c) 2011 Fred Heusschen *	www.frebsite.nl * *	Dual licensed under the MIT and GPL licenses. *	http://en.wikipedia.org/wiki/MIT_License *	http://en.wikipedia.org/wiki/GNU_General_Public_License */(function($) {	$.fn.validVal = function( o ) {		if (this.length > 1) {			return this.each(function() {				$(this).validVal( o );			});		}		var form = this,			opts = $.extend(true, {}, $.fn.validVal.defaults, o),			clss = {				//	key			:  class				'placeholder'	: 'placeholder',				'formatted'		: 'formatted',							'focus'			: 'focus',				'autofocus'		: 'autofocus',				'autotab'		: 'autotab',				'invalid'		: 'invalid',				'inactive'		: 'inactive'			},			vlds = {				//	function	:  class				'required'		: 'required',				'Required'		: 'Required',				'corresponding'	: 'corresponding',				'number'		: 'number',				'email'			: 'email',				'url'			: 'url',				'pattern'		: 'pattern'			};		var inputSelector = 'input:not(:button|:submit|:reset|:hidden), textarea, select';		if ( $.fn.validVal.customValidations ) {			opts.customValidations = $.extend(true, {}, $.fn.validVal.customValidations, opts.customValidations);		}		//	destroy if re-created		if (form.data( 'isValidVal' )) {			form.trigger( 'destroy.vld' );		}		form.data( 'isValidVal', true );		form.bind( 'addField.vld', function( event, el ) {			event.stopPropagation();			var $ff = $(el);			//	overwrite HTML5 attributes			if ( opts.supportHtml5 ) {				var atr = [ 'required', 'autofocus' ],		//	attributes					at2 = [ 'placeholder', 'pattern' ],		//	attributes that need a value					typ = [ 'number', 'email', 'url' ];		//	type-values				if ( vv_test_html5_attr( $ff, 'placeholder' ) && 					$ff.attr( 'placeholder' ).length > 0				) {					var placeholder_value = $ff.attr( 'placeholder' );				}				if ( vv_test_html5_attr( $ff, 'pattern' ) && 					$ff.attr( 'pattern' ).length > 0				) {					var pattern_value = $ff.attr( 'pattern' );				}				for ( var a = 0; a < atr.length; a++ ) {					if ( vv_test_html5_attr( $ff, atr[ a ] ) ) {						$ff.addClass( vv_get_class( atr[ a ] ) );						$ff.removeAttr( atr[ a ] );					}				}				for ( var a = 0; a < at2.length; a++ ) {					if ( vv_test_html5_attr( $ff, at2[ a ] ) &&						$ff.attr( at2[ a ] ).length > 0					) {						$ff.addClass( vv_get_class( at2[ a ] ) );						$ff.removeAttr( at2[ a ] );					}				}				for ( var t = 0; t < typ.length; t++ ) {					if ( vv_test_html5_type( $ff, typ[ t ] ) ) {						$ff.addClass( vv_get_class( typ[ t ] ) );//						$ff.attr( 'type', 'text' );					}				}			}			//	get original value			var original_value = vv_get_original_value( $ff );			if (!placeholder_value) placeholder_value = original_value;			if (!pattern_value) {				if ( vv_is_patternfield( $ff ) ) pattern_value = vv_get_original_value_from_value( $ff, 'alt' );				else pattern_value = original_value;			}			//	reset placeholder			if ( vv_is_placeholderfield( $ff ) ) {				if ( placeholder_value == $ff.val() ) {					original_value = '';				} else if ( original_value == '' ) {					$ff.val( placeholder_value );				}			}			//	save defaults			$ff.data( 'vv_originalvalue', original_value )				.data( 'vv_placeholdervalue', placeholder_value )				.data( 'vv_patternvalue', pattern_value )				.data( 'vv_format', original_value )				.data( 'vv_format_text', '' )				.data( 'vv_type', $ff.attr( 'type' ) );			//	bind events			$ff.bind( 'focus.vld', function( event ) {				event.stopPropagation();				vv_clear_placeholdervalue( $ff );				vv_clear_formatvalue( $ff );				$ff.addClass( vv_get_class( 'focus' ) );			}).bind( 'blur.vld', function( event ) {				event.stopPropagation();				$ff.removeClass( vv_get_class( 'focus' ) );				$ff.trigger( 'validate', opts.validate.onBlur );				vv_restore_formatvalue( $ff );				vv_restore_placeholdervalue( $ff );			}).bind( 'keyup.vld', function( event ) {				event.stopPropagation();				$ff.trigger( 'validate', opts.validate.onKeyup );			}).bind( 'validate.vld', function( event, onEvent ) {				event.stopPropagation();				if ( onEvent === false ) return;				$ff.data( 'vv_isValid', 'valid' );				if ( $ff.is( ':hidden' ) && !opts.validate.hiddenFields ) return;				if ( $ff.is( ':disabled' ) && !opts.validate.disabledFields ) return;				var val = vv_trim( $ff.val() );				for ( var k in vlds ) {					var v = vlds[ k ];					if ( $ff.hasClass( v ) ) {						if ( !eval( 'vv_is_' + k + '( $ff, val )' ) ) {							$ff.data( 'vv_isValid', 'NOT' );							break;						}					}				}				if ( $ff.data( 'vv_isValid' ) == 'valid' ) {					for ( var v in opts.customValidations ) {						var f = opts.customValidations[ v ];						if ( typeof f == 'function' && $ff.hasClass( v ) ) {							if ( !f.call( $ff[0], val ) ) {								$ff.data( 'vv_isValid', 'NOT' );								break;							}						}					}				}				if ( $ff.data( 'vv_isValid' ) == 'valid' ) {					if ( onEvent !== 'invalid' ) {						vv_set_valid( $ff, form, opts );					}				} else {					if ( onEvent !== 'valid' ) {						vv_set_invalid( $ff, form, opts );					}				}			});			//	placeholder			if ( vv_is_placeholderfield( $ff ) ) {				if ( vv_is_placeholder( $ff ) ) {					$ff.addClass( vv_get_class( 'inactive' ) );				}				if ( $ff.is( 'select' ) ) {					$ff.find( 'option:eq(' + $ff.data( 'vv_placeholder_option_number' ) + ')' ).addClass( vv_get_class( 'inactive' ) );							$ff.change(function() {						if ( vv_is_placeholder( $ff ) ) {							$ff.addClass( vv_get_class( 'inactive' ) );						} else {							$ff.removeClass( vv_get_class( 'inactive' ) );						}					});				}			}			//	corresponding			if ( $ff.hasClass( vv_get_class( 'corresponding' ) ) ) {				$('[name=' + $ff.attr( 'alt' ) + ']').blur(function() {					if ( vv_trim( $ff.val() ).length > 0 ) {						vv_clear_formatvalue( $ff );						vv_clear_placeholdervalue( $ff );						$ff.trigger( 'validate', opts.validate.onBlur );						vv_restore_formatvalue( $ff );						vv_restore_placeholdervalue( $ff );					}				});			}			//	autotabbing			if ( $ff.hasClass( vv_get_class( 'autotab' ) ) ) {				var max = $ff.attr( 'maxlength' ),					tab = $ff.attr( 'tabindex' ),					$next = $('[tabindex=' + ( parseInt( tab ) + 1 ) + ']');				if ( $ff.is( 'select' ) ) {					if ( tab ) {						$ff.change(function() {							if ( $next.length ) $next.focus();						});					}				} else {					if ( max && tab ) {						$ff.keyup(function() {							if ( $ff.val().length == max ) {								if ( $next.length ) $next.focus();								$ff.trigger( 'blur' );							}						});					}				}			}			//	autofocus			if ( $ff.hasClass( vv_get_class( 'autofocus' ) ) && !$ff.is( ':disabled' ) ) {				$ff.focus();			}		});		form.bind( 'destroy.vld', function( event ) {			event.stopPropagation();			form.unbind('.vld');			$(inputSelector, form).unbind('.vld');			form.data( 'isValidVal', false );		});		$(inputSelector, form).each(function() {			form.trigger( 'addField.vld', $(this) );		}).filter( 'select, :checkbox, :radio' ).change(function() {			$(this).trigger( 'blur.vld' );		});		form.submitform = function() {			var miss_arr = [],				data_obj = {};			$(inputSelector, form).each(function() {				var $ff = $(this);				vv_clear_placeholdervalue( $ff );				vv_clear_formatvalue( $ff );				$ff.trigger( 'validate', opts.validate.onSubmit );				var n = $ff.attr( 'name' ),					v = $ff.val();				vv_restore_placeholdervalue( $ff );				vv_restore_formatvalue( $ff );				if ( $ff.data( 'vv_isValid' ) == 'valid' ) {					if ( $ff.is( ':radio' ) || $ff.is( ':checkbox' ) ) {						if ( !$ff.is( ':checked' ) ) v = '';					}					if ( v.length > 0 ) {						data_obj[ n ] = v;					}				} else if ( opts.validate.onSubmit !== false ) {												miss_arr.push( $ff );				}			});			if ( miss_arr.length > 0 ) {				if ( opts.invalidFormFunc ) {					opts.invalidFormFunc.call( form[0], miss_arr, form, opts.language );				}				form.attr( 'validate', 'false' );				return false;			} else {				$('input:text', form ).each(function() {					var $ff = $(this);					vv_clear_placeholdervalue( $ff );					vv_clear_formatvalue( $ff );				});				if ( opts.onSubmit ) {					opts.onSubmit.call( form[0], opts.language );				}				form.attr( 'validate', 'true' );				return data_obj;			}		};		form.resetform = function() {			$(inputSelector, form).each(function() {				var $ff = $(this);				if ( vv_is_placeholderfield( $ff ) ) {					$ff.addClass( vv_get_class( 'inactive' ) );					$ff.val( $ff.data( 'vv_placeholdervalue' ) );				} else {					$ff.val( $ff.data( 'vv_originalvalue' ) );				}				vv_set_valid( $ff, form, opts );			});			if ( opts.onReset ) {				opts.onReset.call( form[0], opts.language );			}		};		if ( form.is( 'form' ) ) {			form.attr( 'novalidate', 'novalidate' );			form[0].onsubmit = function() {				return form.submitform();			};			form[0].onreset = function() {				form.resetform();				return false;			};		}		return this;	};	$.fn.validVal.defaults = {		supportHtml5		: true,		language			: 'en',		customValidations	: {},		validate			: {			'onBlur'			: true,			'onSubmit'			: true,			'onKeyup'			: false,			'hiddenFields'		: false,			'disabledFields'	: false		},		invalidFieldFunc	: function( $form, language ) {			if ( $(this).is( ':radio' ) || $(this).is( ':checkbox' ) ) {				$(this).parent().addClass( vv_get_class( 'invalid' ) );			}			$(this).addClass( vv_get_class( 'invalid' ) );		},		validFieldFunc		: function( $form, language ) {			if ( $(this).is( ':radio' ) || $(this).is( ':checkbox' ) ) {				$(this).parent().removeClass( vv_get_class( 'invalid' ) );			}			$(this).removeClass( vv_get_class( 'invalid' ) );		},		invalidFormFunc		: function( field_arr, language ) { 			switch ( language ) {				case 'nl':					msg = 'Let op, niet alle velden zijn correct ingevuld.';					break;				case 'de':					msg = 'Achtung, nicht alle Felder sind korrekt ausgefuellt.';					break;				case 'es':					msg = 'Atención, no se han completado todos los campos correctamente.';					break;                case 'cn':                    msg = '請填寫必填項';				case 'en':				default:					msg = 'Attention, not all the fields have been filled out correctly.';					break;			}			//alert( msg );			field_arr[0].focus();		}	};	//	validations	function vv_is_required( $f, v ) {		if ( $f.is( ':radio' ) || $f.is( ':checkbox' ) ) {			var attr = ( $f.is( ':checkbox' ) ) ? 'alt' : 'name';			if ( typeof $f.attr( attr ) != 'undefined' && $f.attr( attr ).length > 0 ) {				$f = $( 'input[' + attr + '=' + $f.attr( attr ) + ']' );			}			if ( !$f.is( ':checked' ) ) return false;		} else if ( $f.is( 'select' ) ) {			if ( vv_is_placeholderfield( $f ) ) {				if ( vv_is_placeholder( $f ) ) return false;			} else {				if ( v.length == 0 ) return false;			}		} else {			if ( v.length == 0 ) return false;		}	 	return true;	}	function vv_is_Required( $f, v ) {		return vv_is_required( $f, v );	}	function vv_is_number( $f, v ) {		v = vv_strip_whitespace( v );		if ( v.length == 0 ) return true;		if ( isNaN( v ) ) return false;		return true;	}	function vv_is_email( $f, v ) {		if ( v.length == 0 ) return true;		var r = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;    	return r.test( v );	}	function vv_is_url( $f, v ) {        if ( v.length == 0 ) return true;        if ( v.match(/^www\./) ) v = "http://" + v;        return v.match(/^(http\:\/\/|https\:\/\/)(.{4,})$/);	}	function vv_is_pattern( $f, v ) {		if ( v.length == 0 ) return true;		var p = $f.data( 'vv_patternvalue' ),        	r = new RegExp( p.substr( 1, p.length - 2 ) );        return r.test( v );	}	function vv_is_corresponding( $f, v ) {		if ( $f.val() != $('[name=' + $f.attr( 'alt' ) + ']').val() ) return false;		return true;	}	//	placeholder functions	function vv_is_placeholder( $f ) {		if ( vv_trim( $f.val() ) == $f.data( 'vv_placeholdervalue' ) ) return true;		return false;	}	function vv_is_placeholderfield( $f ) {		if ( $f.hasClass( vv_get_class( 'placeholder' ) ) ) return true;		return false;	}	function vv_clear_placeholdervalue( $f ) {		if ( vv_is_placeholderfield( $f ) ) {			if ( vv_is_placeholder( $f ) && !$f.is( 'select' )  ) {				$f.val( '' );				$f.removeClass( vv_get_class( 'inactive' ) );			}		}	}	function vv_restore_placeholdervalue( $f ) {		if ( vv_is_placeholderfield( $f ) ) {			if ( vv_trim( $f.val() ) == '' && !$f.is( 'select' ) ) {				$f.val(  $f.data( 'vv_placeholdervalue' ) );				$f.addClass( vv_get_class( 'inactive' ) );			}		}	}	//	pattern functions	function vv_is_patternfield( $f ) {		if ( $f.hasClass( vv_get_class( 'pattern' ) ) ) return true;		return false;	}	//	formatted functions	function vv_is_formattedfield( $f ) {		if ( $f.hasClass( vv_get_class( 'formatted' ) ) ) return true;		else return false;	}	function vv_clear_formatvalue( $f ) {		if ( vv_is_formattedfield( $f ) ) {			$f.val( $f.data( 'vv_format_text' ) );		}	}	function vv_restore_formatvalue( $f ) {		if ( vv_is_formattedfield( $f ) ) {			var o = vv_strip_whitespace( $f.val() ),				v = $f.data( 'vv_format' );			$f.data( 'vv_format_text', o );			for ( var a = 0; a < o.length && a < v.length; a++ ) {				v = v.replace( '_', o[ a ] );			}			$f.val( v );		}	}	//	valid/invalid	function vv_set_valid( $f, f, o ) {		if ( o.validFieldFunc ) {			o.validFieldFunc.call( $f[0], f, o.language );		}	}	function vv_set_invalid( $f, f, o ) {		if ( o.invalidFieldFunc ) {			o.invalidFieldFunc.call( $f[0], f, o.language );		}	}	//	HTML5 stuff	function vv_test_html5_attr( $f, a ) {		if ( typeof $f.attr( a ) == 'undefined' ) 	return false;	// non HTML5 browsers		if ( $f.attr( a ) === 'false' || 			$f.attr( a ) === false ) 				return false;	// HTML5 browsers		return true;	}	function vv_test_html5_type( $f, t ) {		if ( $f.attr( 'type' ) == t ) 				return true;	// cool HTML5 browsers		if ( $f.is( 'input[type="' + t + '"]' ) ) 	return true;	// non-HTML5 but still cool browsers		//	non-HTML5, non-cool browser		var res = vv_get_outerHtml( $f );		if ( res.indexOf( 'type="' + t + '"' ) != -1 ||			res.indexOf( 'type=\'' + t + '\'' ) != -1 ||			res.indexOf( 'type=' + t + '' ) != -1		) {			return true;		}		return false;	}	//	misc	function vv_get_original_value( $f ) {		var val = vv_get_outerHtml( $f ),			lal = val.toLowerCase();		if ( $f.is( 'select' ) ) {			num = 0;			$f.find( '> option' ).each(function( n ) {				val = vv_get_outerHtml( $(this) );				var qal = val.split( "'" ).join( '"' ).split( '"' ).join( '' );				qal = qal.substr( 0, qal.indexOf( '>' ) );				if ( qal.indexOf( 'selected=selected' ) > -1 ) {					num = n;				}			});			$f.data( 'vv_placeholder_option_number', num );			return vv_get_original_value_from_value( $f.find( '> option:nth(' + num + ')' ) );		} else if ( $f.is( 'textarea' ) ) {			val = val.substr( val.indexOf( '>' ) + 1 );			val = val.substr( 0, val.indexOf( '</t' ) );			return val;		} else {			return vv_get_original_value_from_value( $f );		}	}	function vv_get_original_value_from_value( $f, at ) {		if ( typeof at == 'undefined' ) at = 'value';		var val = vv_get_outerHtml( $f ),			lal = val.toLowerCase();		if ( lal.indexOf( at + '=' ) > -1 ) {			val = val.substr( lal.indexOf( at + '=' ) + ( at.length + 1 ) );			var quot = val.substr( 0, 1 );			if ( quot == '"' || quot == "'" ) {				val = val.substr( 1 );				val = val.substr( 0, val.indexOf( quot ) );			} else {				val = val.substr( 0, val.indexOf( ' ' ) );			}			return val;		} else {			return '';		}	}	function vv_get_outerHtml( $e ) {		return $( '<div></div>' ).append( $e.clone() ).html();	}	function vv_get_class( cl ) {		if ( typeof clss != 'undefined' && typeof clss[ cl ] != 'undefined' ) return clss[ cl ];		return cl;	}	function vv_trim( str ) {		if ( typeof str == 'undefined' ) return '';		if ( str.length == 0 ) return '';		return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');	}	function vv_strip_whitespace( str ) {		str = vv_trim( str );		var r = [ ' ', '-', '+', '(', ')', '/', '\\' ];		for ( var i = 0; i < r.length; i++ ) {			str = str.split( r[ i ] ).join( '' );		}		return str;	}})(jQuery);