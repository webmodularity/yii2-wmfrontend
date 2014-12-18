(function ($) {
    if (!window.WM) { window.WM = {}; }
    if (!WM.contact) { WM.contact = {}; }

    $.extend(WM.contact, {
        postForm: function (contactForm, attributeIds) {
            var submitButton = contactForm.find('button');
            var submitButtonCopy = submitButton.html();
            var nameInput = contactForm.find('#'+attributeIds.name);
            var emailInput = contactForm.find('#'+attributeIds.email);
            var messageInput = contactForm.find('#'+attributeIds.message);
            // Remove Old error messages
            contactForm.find('.error-message').remove();
            nameInput.removeClass('inputError');
            emailInput.removeClass('inputError');
            messageInput.removeClass('inputError');
            // Validate
            var validateError = this.formValidate(nameInput, emailInput, messageInput);
            if (validateError === true) {
                this.setSubmitError(submitButton, submitButtonCopy, "Error - Message Not Sent!");
            } else {
                var formData = contactForm.serialize();
                nameInput.prop('disabled', true);
                emailInput.prop('disabled', true);
                messageInput.prop('disabled', true);
                submitButton.html("Sending.....");
                $.ajax({
                    type: 'POST',
                    url: contactForm.attr('action'),
                    data: formData
                }).done(function(response) {
                    var responseObject = $.parseJSON(response);
                    if (responseObject.responseCode == 200) {
                        submitButton.html("Thanks - Message Received");
                        nameInput.val('');
                        emailInput.val('');
                        messageInput.val('Thanks, your message has been sent and we will respond to you ASAP.')
                            .prop('disabled', false);
                    }
                    setTimeout(function(){
                        submitButton.prop('disabled', false).removeClass('btn-error').html(submitButtonCopy);
                        nameInput.prop('disabled', false);
                        emailInput.prop('disabled', false);
                        messageInput.val('').prop('disabled', false);
                        submitButton.html(submitButtonCopy);
                        grecaptcha.reset();
                    },10000);
                }).fail(function(data) {
                    WM.contact.setSubmitError(submitButton, submitButtonCopy, data.responseText);
                });
            }
        },
        captchaCallback: function (recaptchaInput) {
            alert('successful captcha!');
        },
        formValidate: function (nameInput, emailInput, messageInput) {
            var hasError = false;
            // Name
            if (this.inputIsEmpty(nameInput)) {
                this.setInputError(nameInput, 'Name is required!');
                hasError = true;
            }
            // Email
            if (this.inputIsEmpty(emailInput)) {
                this.setInputError(emailInput, 'Email is required!');
                hasError = true;
            } else {
                var emailReg = /^[a-zA-Z0-9.!#$%&amp;'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
                if(!emailReg.test($.trim(emailInput.val()))) {
                    this.setInputError(emailInput, 'Invalid Email Address!');
                    hasError = true;
                }
            }
            // Message
            if (this.inputIsEmpty(messageInput)) {
                this.setInputError(messageInput, 'Message is required!');
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
        setInputError: function (input, errorText) {
            input.parents('.controls').append('<span class="error-message" style="display:none;">' + errorText + '</span>').find('.error-message').fadeIn('fast');
            input.addClass('inputError');
        },
        setSubmitError: function (submitButton, submitButtonCopy, errorMessage) {
            submitButton.prop('disabled', true).html(''+errorMessage).addClass('btn-error');
            setTimeout(function(){
                submitButton.prop('disabled', false).removeClass('btn-error').html(submitButtonCopy);
            },2000);
        }
    });
})(jQuery);