;(function ($, win) {
    
    'use strict';
    
    var self = win.SolidGlobal = {

        init: function () {

            self.attachFormAjaxErrorHandler();

            self.attachGlobalConfirmHandler();

            self.addCsrf()

            $(document).trigger('render');
        },

        showAjaxErrorMessage: function (event, msg) {
            $.oc.flashMsg({
                text: msg,
                class: 'error',
                interval: 5
            });
            event.preventDefault();
        },

        addCsrf: function () {
            /*
             * Ensure the CSRF token is added to all AJAX requests.
             */
            $.ajaxPrefilter(function(options) {
                var token = $('meta[name="csrf-token"]').attr('content')

                if (token) {
                    if (!options.headers) options.headers = {}
                    options.headers['X-CSRF-TOKEN'] = token
                }
            })  
        },

        /**
         * Various global AJAX form error handler.
         * These hooks into Global october framework.js event
         * 
         * @return void
         */
        attachFormAjaxErrorHandler: function () {

            /**
             * When global error message given by AJAX handler,
             * render using flash message
             */
            $(window).on('ajaxError', function (event, context, status, jqxhr) {
                var data = {}
                if (context.responseJSON) {
                    data = context;
                } else if (jqxhr.responseJSON) {
                    data = jqxhr;
                }

                if (!data.responseJSON) return;

                if (data.responseJSON['X_OCTOBER_ERROR_FIELDS']
                        && data.responseJSON['X_OCTOBER_ERROR_FIELDS']['ajax_dump']) {

                    // Hide october's backend
                    $('.flash-message.error')
                        .hide()

                    event.preventDefault()

                    $.popup({
                        content: data.responseJSON['X_OCTOBER_ERROR_FIELDS']['ajax_dump']
                    })
                }

            })

            /**
             * When global error message given by AJAX handler, 
             * render using flash message
             */
            $(window).on('ajaxErrorMessage', function (event, msg) {
                self.showAjaxErrorMessage(event, msg);
            });

            /**
             * Error event is triggered on each element, use this
             * to mark form fields with error
             */
            $(window).on('ajaxInvalidField', function(e, el, fieldName, fieldMessages, isFirstInvalidField) {
                $(el).addClass('has-error js-ajax-error');
                $(el).closest('.form-group')
                    .addClass('has-error js-ajax-error');
                var $form = $(el).closest('form'),
                    $window = $(window);

                if ($form.hasClass('js-custom-error')) return;

                if ($form.length) {

                    if (isFirstInvalidField) {
                        var $alert = $('<div class="form-alert form-alert--errors js-form-error"><ul class="form-alert__list"></ul></div>');
                        $alert.prependTo($form);

                        // Also cancel ajaxErrorMessage event
                        $window.off('ajaxErrorMessage');
                        $window.on('ajaxErrorMessage', function (e, msg) {
                            $.oc.flashMsg({
                                text: 'Oops, please correct form errors.',
                                class: 'error',
                                interval: 5
                            });
                            e.preventDefault();
                        });

                        // When completed, bind global ajax error message again.
                        $form.on('ajaxComplete', function (e, context, textStatus, jqXHR) {
                            $window.off('ajaxErrorMessage');
                            $window.on('ajaxErrorMessage', function (e, msg) {
                                self.showAjaxErrorMessage(event, msg);
                            });
                        });
                    }

                    var $alertList = $form.find('.form-alert__list');
                    $.each(fieldMessages, function (idx, val) {
                        $alertList.append('<li>'+val+'</li>');
                    });
                }


            });

            /**
             * Before a new request is sent, clean up form errors
             * ensuring we are always at clean state
             */
            $(window).on('ajaxBeforeSend', function (e) {
                $('.js-form-error').remove();
                $('.js-ajax-error').removeClass('has-error js-ajax-error');      
            });
        },

        /**
         * Global request-confirm handler, preventing default javascript confirm
         */
        attachGlobalConfirmHandler: function () {
            $(window).on('ajaxConfirmMessage', function(event, message) {
                if (!message) return


                swal({
                    title: message,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d9534f",
                    confirmButtonText: "Yes",
                    cancelButtonText: "Cancel",
                    closeOnConfirm: true,
                    closeOnCancel: true,
                    customClass: 'icon-danger two-buttons',
                },
                function(isConfirm){
                    isConfirm
                        ? event.promise.resolve()
                        : event.promise.reject()
                });

                // Prevent the default confirm() message
                event.preventDefault()
                return true
            })
        }
        
    };
    
})(jQuery, window);