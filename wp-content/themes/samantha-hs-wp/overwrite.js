/*
 * ====================================================================
 * growp
 * @package  growp
 * @author   GrowGroup.Inc <info@grow-group.jp>
 * @license  MIT Licence
 * ====================================================================
 */

(function ($) {
    var growApp = function () {

    };

    /************************
    * please insert your code
    *************************/
    growApp.prototype.myCode = function () {

    }

    $(function () {
        var app = new growApp();
        app.myCode();
    });
})(jQuery);

// フォーム2重送信防止
(function($){
	$(function(){
		var mw_wp_form_button_no_click = true;
		$( '.mw_wp_form button[type="submit"]' ).click( function() {
			var formElement = $( this ).closest( 'form' )[0];
			if ( formElement && formElement.checkValidity && !formElement.checkValidity() ) {
				return;
			}
			if ( mw_wp_form_button_no_click ) {
				mw_wp_form_button_no_click = false;
			} else {
				$( this ).prop( 'disabled', true );
			}
		} );
	})
})(jQuery)
