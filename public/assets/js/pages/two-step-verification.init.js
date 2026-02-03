
const TOTAL_DIGITS = 6;

function getInputElement(index) {
    return document.getElementById('digit' + index + '-input');
}

function moveToNext(index, event) {
    const key = event.keyCode || event.which;
    const input = getInputElement(index);

    // allow only numbers
    if (input.value && !/^\d$/.test(input.value)) {
        input.value = '';
        return;
    }

    // move forward when a digit is entered
    if (input.value.length === 1 && index < TOTAL_DIGITS) {
        getInputElement(index + 1).focus();
    }

    // backspace → move to previous
    if (key === 8 && index > 1 && input.value === '') {
        getInputElement(index - 1).focus();
    }

    // auto submit when last digit filled
    if (index === TOTAL_DIGITS && input.value.length === 1) {
        submitOtp();
    }
}

function submitOtp() {
    let otp = '';

    for (let i = 1; i <= TOTAL_DIGITS; i++) {
        const val = getInputElement(i).value;
        if (val === '') {
            getInputElement(i).focus();
            return;
        }
        otp += val;
    }

    console.log('OTP:', otp);

    // if you have hidden input
    const otpInput = document.getElementById('otp');
    if (otpInput) {
        otpInput.value = otp;
    }

    // submit form
    document.getElementById('storeForm').submit();
}