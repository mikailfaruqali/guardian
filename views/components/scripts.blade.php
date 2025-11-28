<script>
    const OTPHandler = {
        init() {
            this.attachEventListeners();
            this.focusFirstInput();
        },

        attachEventListeners() {
            $('.otp-input').on('input', (e) => this.handleInput(e));
            $('.otp-input').on('keydown', (e) => this.handleKeydown(e));
            $('.otp-input').on('paste', (e) => this.handlePaste(e));
        },

        handleInput(e) {
            const $input = $(e.target);
            const value = $input.val();

            if (!this.isNumeric(value)) {
                return $input.val('');
            }

            if (value) {
                this.focusNext($input);
            }

            this.updateCode($input);
        },

        handleKeydown(e) {
            const $input = $(e.target);

            const actions = {
                'Backspace': () => {
                    if (!$input.val()) {
                        this.focusPrev($input);
                    }
                },
                'ArrowLeft': () => this.focusPrev($input),
                'ArrowRight': () => this.focusNext($input)
            };

            if (actions[e.key]) {
                actions[e.key]();
            }
        },

        handlePaste(e) {
            e.preventDefault();

            const pastedData = e.originalEvent.clipboardData.getData('text').slice(0, 6);

            if (!this.isNumeric(pastedData)) {
                return;
            }

            this.fillInputs(pastedData);
            this.updateCode($('.otp-input').first());
            this.focusInputAt(Math.min(pastedData.length - 1, 5));
        },

        isNumeric(value) {
            return /^[0-9]+$/.test(value);
        },

        focusNext($input) {
            const $next = $input.next('.otp-input');

            if ($next.length) {
                $next.focus();
            }
        },

        focusPrev($input) {
            const $prev = $input.prev('.otp-input');

            if ($prev.length) {
                $prev.focus();
            }
        },

        focusInputAt(index) {
            $('.otp-input').eq(index).focus();
        },

        focusFirstInput() {
            $('.otp-input').first().focus();
        },

        fillInputs(data) {
            const $inputs = $('.otp-input');

            [...data].forEach((char, i) => {
                $inputs.eq(i).val(char);
            });
        },

        updateCode($input) {
            const code = $('.otp-input').get().map(input => $(input).val()).join('');

            $input.closest('form').find('input[name="code"]').val(code);

            if (code.length === 6) {
                $input.closest('form').submit();
            }
        }
    };

    $(document).ready(() => OTPHandler.init());
</script>
