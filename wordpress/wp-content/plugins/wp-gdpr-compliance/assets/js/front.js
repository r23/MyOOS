(function (window, document, undefined) {
    'use strict';

    /**
     * @param data
     * @returns {string}
     * @private
     */
    var ajaxLoading = false,
        ajaxURL = wpgdprcData.ajaxURL,
        ajaxSecurity = wpgdprcData.ajaxSecurity,
        _objectToParametersString = function (data) {
            return Object.keys(data).map(function (key) {
                var value = data[key];
                if (typeof value === 'object') {
                    value = JSON.stringify(value);
                }
                return key + '=' + value;
            }).join('&');
        },
        /**
         * @param $checkboxes
         * @returns {Array}
         * @private
         */
        _getValuesByCheckedBoxes = function ($checkboxes) {
            var output = [];
            if ($checkboxes.length) {
                $checkboxes.forEach(function (e) {
                    var value = parseInt(e.value);
                    if (e.checked && value > 0) {
                        output.push(value);
                    }
                });
            }
            return output;
        },
        /**
         * @param data
         * @param values
         * @param $form
         * @param delay
         * @private
         */
        _doAjax = function (data, values, $form, delay) {
            var $feedback = $form.querySelector('.wpgdprc-message'),
                value = values.slice(0, 1);
            if (value.length > 0) {
                var $row = $form.querySelector('tr[data-id="' + value[0] + '"]');
                $row.classList.remove('wpgdprc-status--error');
                $row.classList.add('wpgdprc-status--processing');
                $feedback.setAttribute('style', 'display: none;');
                $feedback.classList.remove('wpgdprc-message--error');
                $feedback.innerHTML = '';
                setTimeout(function () {
                    var request = new XMLHttpRequest();
                    data.data.value = value[0];
                    request.open('POST', ajaxURL);
                    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8');
                    request.send(_objectToParametersString(data));
                    request.addEventListener('load', function () {
                        if (request.response) {
                            var response = JSON.parse(request.response);
                            $row.classList.remove('wpgdprc-status--processing');
                            if (response.error) {
                                $row.classList.add('wpgdprc-status--error');
                                $feedback.innerHTML = response.error;
                                $feedback.classList.add('wpgdprc-message--error');
                                $feedback.removeAttribute('style');
                            } else {
                                values.splice(0, 1);
                                $row.querySelector('input[type="checkbox"]').remove();
                                $row.classList.add('wpgdprc-status--removed');
                                _doAjax(data, values, $form, 500);
                            }
                        }
                    });
                }, (delay || 0));
            }
        },
        /**
         * @param data
         * @param days
         * @private
         */
        _saveCookie = function (data, days) {
            data = (data) ? data : '';
            days = (days) ? days : 365;
            var date = new Date();
            date.setTime(date.getTime() + 24 * days * 60 * 60 * 1e3);
            document.cookie = 'wpgdprc-consent=' + encodeURIComponent(data) + '; expires=' + date.toGMTString() + '; path=/';
        },
        /**
         * @param name
         * @returns {*}
         * @private
         */
        _readCookie = function (name) {
            if (name) {
                for (var e = encodeURIComponent(name) + "=", o = document.cookie.split(";"), r = 0; r < o.length; r++) {
                    for (var n = o[r]; " " === n.charAt(0);) {
                        n = n.substring(1, n.length);
                    }
                    if (n.indexOf(e) === 0) {
                        return decodeURIComponent(n.substring(e.length, n.length));
                    }
                }
            }
            return null;
        },
        initConsentBar = function () {
            var $consentBar = document.querySelector('.wpgdprc-consent-bar');
            if ($consentBar === null) {
                return;
            }

            $consentBar.style.display = 'block';

            var $button = $consentBar.querySelector('.wpgdprc-consent-bar__button');
            if ($button !== null) {
                $button.addEventListener('click', function (e) {
                    e.preventDefault();
                    _saveCookie('accept');
                    window.location.reload(true);
                });
            }
        },
        initConsentModal = function () {
            var $consentModal = document.querySelector('#wpgdprc-consent-modal');
            if ($consentModal === null) {
                return;
            }

            MicroModal.init({
                disableScroll: true,
                disableFocus: true,
                onClose: function ($consentModal) {
                    var $descriptions = $consentModal.querySelectorAll('.wpgdprc-consent-modal__description'),
                        $buttons = $consentModal.querySelectorAll('.wpgdprc-consent-modal__navigation > a'),
                        $checkboxes = $consentModal.querySelectorAll('input[type="checkbox"]');

                    if ($descriptions.length > 0) {
                        for (var i = 0; i < $descriptions.length; i++) {
                            $descriptions[i].style.display = ((i === 0) ? 'block' : 'none');
                        }
                    }
                    if ($buttons.length > 0) {
                        for (var i = 0; i < $buttons.length; i++) {
                            $buttons[i].classList.remove('wpgdprc-button--active');
                        }
                    }
                    if ($checkboxes.length > 0) {
                        for (var i = 0; i < $checkboxes.length; i++) {
                            $checkboxes[i].checked = false;
                        }
                    }
                }
            });

            var $settingsLink = document.querySelector('.wpgdprc-consents-settings-link');
            if ($settingsLink !== null) {
                $settingsLink.addEventListener('click', function (e) {
                    e.preventDefault();
                    MicroModal.show('wpgdprc-consent-modal');
                });
            }

            var $buttons = $consentModal.querySelectorAll('.wpgdprc-consent-modal__navigation > a');
            if ($buttons.length > 0) {
                var $descriptions = $consentModal.querySelectorAll('.wpgdprc-consent-modal__description');
                for (var i = 0; i < $buttons.length; i++) {
                    $buttons[i].addEventListener('click', function (e) {
                        e.preventDefault();
                        var $target = $consentModal.querySelector('.wpgdprc-consent-modal__description[data-target="' + this.dataset.target + '"]');
                        if ($target !== null) {
                            for (var i = 0; i < $buttons.length; i++) {
                                $buttons[i].classList.remove('wpgdprc-button--active');
                            }
                            this.classList.add('wpgdprc-button--active');
                            for (var i = 0; i < $descriptions.length; i++) {
                                $descriptions[i].style.display = 'none';
                            }
                            $target.style.display = 'block';
                        }
                    });
                }
            }

            var $buttonSave = $consentModal.querySelector('.wpgdprc-button--secondary');
            if ($buttonSave !== null) {
                $buttonSave.addEventListener('click', function (e) {
                    e.preventDefault();
                    var $checkboxes = $consentModal.querySelectorAll('input[type="checkbox"]'),
                        checked = [];

                    if ($checkboxes.length > 0) {
                        for (var i = 0; i < $checkboxes.length; i++) {
                            var $checkbox = $checkboxes[i],
                                value = $checkbox.value;
                            if ($checkbox.checked === true && !isNaN(value)) {
                                checked.push(parseInt(value));
                            }
                        }
                        if (checked.length > 0) {
                            _saveCookie(checked);
                        } else {
                            _saveCookie('decline');
                        }
                    }

                    window.location.reload(true);
                });
            }
        },
        initFormAccessRequest = function () {
            var $formAccessRequest = document.querySelector('.wpgdprc-form--access-request');
            if ($formAccessRequest === null) {
                return;
            }

            var $feedback = $formAccessRequest.querySelector('.wpgdprc-message'),
                $emailAddress = $formAccessRequest.querySelector('#wpgdprc-form__email'),
                $consent = $formAccessRequest.querySelector('#wpgdprc-form__consent');

            $formAccessRequest.addEventListener('submit', function (e) {
                e.preventDefault();
                if (!ajaxLoading) {
                    ajaxLoading = true;
                    $feedback.style.display = 'none';
                    $feedback.classList.remove('wpgdprc-message--success', 'wpgdprc-message--error');
                    $feedback.innerHTML = '';

                    var data = {
                            action: 'wpgdprc_process_action',
                            security: ajaxSecurity,
                            data: {
                                type: 'access_request',
                                email: $emailAddress.value,
                                consent: $consent.checked
                            }
                        },
                        request = new XMLHttpRequest();

                    data = _objectToParametersString(data);
                    request.open('POST', ajaxURL, true);
                    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8');
                    request.send(data);
                    request.addEventListener('load', function () {
                        if (request.response) {
                            var response = JSON.parse(request.response);
                            if (response.message) {
                                $formAccessRequest.reset();
                                $emailAddress.blur();
                                $feedback.innerHTML = response.message;
                                $feedback.classList.add('wpgdprc-message--success');
                                $feedback.removeAttribute('style');
                            }
                            if (response.error) {
                                $emailAddress.focus();
                                $feedback.innerHTML = response.error;
                                $feedback.classList.add('wpgdprc-message--error');
                                $feedback.removeAttribute('style');
                            }
                        }
                        ajaxLoading = false;
                    });
                }
            });
        },
        initFormDeleteRequest = function () {
            var $formDeleteRequest = document.querySelectorAll('.wpgdprc-form--delete-request');
            if ($formDeleteRequest.length < 1) {
                return;
            }

            $formDeleteRequest.forEach(function ($form) {
                var $selectAll = $form.querySelector('.wpgdprc-select-all');

                $form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    var $this = e.target,
                        $checkboxes = $this.querySelectorAll('.wpgdprc-checkbox'),
                        data = {
                            action: 'wpgdprc_process_action',
                            security: ajaxSecurity,
                            data: {
                                type: 'delete_request',
                                session: wpgdprcData.session,
                                settings: JSON.parse($this.dataset.wpgdprc)
                            }
                        };
                    $selectAll.checked = false;
                    _doAjax(data, _getValuesByCheckedBoxes($checkboxes), $this);
                });

                if ($selectAll !== null) {
                    $selectAll.addEventListener('change', function (e) {
                        var $this = e.target,
                            checked = $this.checked,
                            $checkboxes = $form.querySelectorAll('.wpgdprc-checkbox');
                        $checkboxes.forEach(function (e) {
                            e.checked = checked;
                        });
                    });
                }
            });
        };

    document.addEventListener('DOMContentLoaded', function () {
        if (_readCookie('wpgdprc-consent') === null) {
            initConsentBar();
        }
        initConsentModal();
        initFormAccessRequest();
        initFormDeleteRequest();
    });
})(window, document);