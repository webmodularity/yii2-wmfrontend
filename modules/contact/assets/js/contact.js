(function ($) {
    if (!window.wm) { window.wm = {}; }
    if (!wm.frontend) { wm.frontend = {}; }
    if (!wm.frontend.contact) { wm.frontend.contact = {}; }

    $.extend(wm.frontend.contact, {
        fields: ['name', 'email', 'message'],
        inputs: {},
        emailReg: /^[a-zA-Z0-9.!#$%&amp;'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/,
        nameReg: /^[\w\s'.-]+$/,
        postForm: function (contactForm, attributeIds) {
            var _this = this;
            // define inputs object for quick access
            _this.inputs.submit = contactForm.find('button');
            _this.inputs.submitHtml =  _this.inputs.submit.html();
            $.each(_this.fields, function(index, value) {
                _this.inputs[value] = contactForm.find('#'+attributeIds[value]);
            });
            _this.inputs.alert = contactForm.find("#contact-alert");
            // Validate Client-side
            if (this.formValidateClient() === true) {
                _this.flashSubmitError("Error - Message Not Sent!");
            } else {
                var formData = contactForm.serialize();
                _this.toggleInputs();
                _this.toggleSubmit();
                _this.setSubmitHtml("Sending.....");
                $.ajax({
                    type: 'POST',
                    url: contactForm.attr('action'),
                    data: formData
                }).done(function(response) {
                    var responseObject = $.parseJSON(response);
                    if (responseObject.responseCode == 200) {
                        _this.setSubmitHtml("Thanks - Message Received");
                        _this.flashAlert('success', 'Thanks, your message has been sent and we will respond to you ASAP.');
                        setTimeout(function(){
                            _this.toggleSubmit();
                            _this.toggleInputs();
                            _this.clearInputs();
                            _this.resetSubmitHtml();
                            _this.reloadRecaptcha();
                        },5000);
                    } else {
                        var errors = {};
                        var clearVal = false;
                        $.each(responseObject.errors, function (index, value) {
                            if (index === 'captcha') {
                                errors.captcha = value[0];
                            } else {
                                if (index === 'name' || index === 'email') {
                                    clearVal = true;
                                }
                                _this.setInputError(_this.inputs[index], value[0], clearVal);
                            }
                            clearVal = false;
                        });
                        _this.toggleInputs();
                        _this.toggleSubmit();
                        if (errors.hasOwnProperty('captcha')) {
                            _this.flashAlert('danger', errors.captcha);
                        }
                        _this.flashSubmitError("Error - Message Not Sent!");
                        _this.reloadRecaptcha();
                    }
                }).fail(function(data) {
                    _this.flashSubmitError(data.responseText);
                });
            }
        },
        formValidateClient: function () {
            var hasError = false;
            // Name
            if (this.inputIsEmpty(this.inputs.name)) {
                this.setInputError(this.inputs.name, 'Name is required!');
                hasError = true;
            } else if (this.inputs.name.val().length > 255) {
                this.setInputError(this.inputs.name, 'Name is too long!', true);
                hasError = true;
            }
            // Email
            if (this.inputIsEmpty(this.inputs.email)) {
                this.setInputError(this.inputs.email, 'Email is required!');
                hasError = true;
            } else if (!this.emailReg.test($.trim(this.inputs.email.val()))) {
                this.setInputError(this.inputs.email, 'Invalid Email Address!', true);
                hasError = true;
            } else if (this.inputs.email.val().length > 255) {
                this.setInputError(this.inputs.email, 'Email is too long!', true);
                hasError = true;
            }
            // Message
            if (this.inputIsEmpty(this.inputs.message)) {
                this.setInputError(this.inputs.message, 'Message is required!');
                hasError = true;
            } else if (this.inputs.message.val().length > 10000) {
                this.setInputError(this.inputs.message, 'Message is too long!');
                hasError = true;
            }
            return hasError;
        },
        inputIsEmpty: function (input) {
            if ($.trim(input.val()) == '') {
                return true;
            } else {
                return false;
            }
        },
        setInputError: function (input, errorText, clearVal) {
            clearVal = clearVal === true ? true : false;
            input.parents('.controls').append('<span class="error-message" style="display:none;">' + errorText + '</span>').find('.error-message').fadeIn('slow');
            input.addClass('inputError');
            if (clearVal === true) {
                input.val('');
            }
        },
        removeInputError: function(input) {
            input.parents('.controls').find('.error-message').remove().fadeOut("slow");
            input.removeClass('inputError');
        },
        flashSubmitError: function(html, timeout) {
            var _this = this;
            html = (typeof html === "string") ? html : '';
            timeout = (typeof timeout === "number") ? timeout : 5000;
            _this.toggleSubmit();
            _this.inputs.submit.addClass('btn-error').html(html);
            setTimeout(function(){
                _this.inputs.submit.removeClass('btn-error');
                _this.resetSubmitHtml();
                _this.toggleSubmit();
            }, timeout);
        },
        setSubmitHtml: function(html) {
            this.inputs.submit.html(html);
        },
        resetSubmitHtml: function() {
            this.inputs.submit.html(this.inputs.submitHtml);
        },
        toggleInputs: function () {
            var _this = this;
            $.each(_this.fields, function(index, value) {
                var state = _this.inputs[value].prop('disabled') ? false : true;
                _this.inputs[value].prop('disabled', state);
            });
        },
        toggleSubmit: function() {
            var buttonState = this.inputs.submit.prop('disabled') ? false : true;
            this.inputs.submit.prop('disabled', buttonState);
        },
        clearInputs: function() {
            var _this = this;
            $.each(_this.fields, function(index, value) {
                _this.inputs[value].val('');
            });
        },
        reloadRecaptcha: function() {
            grecaptcha.reset();
        },
        flashAlert: function(type, html, timeout) {
            var _this = this;
            timeout = (typeof timeout === "number") ? timeout : 5000;
            _this.inputs.alert.append("<div class='alert alert-"+type+"' role='alert'>"+html+"</div>").hide().fadeIn("slow");
            setTimeout(function(){
                _this.inputs.alert.find('div').fadeOut("slow", function() {
                    this.remove();
                });
            }, timeout);
        }
    });
})(jQuery);