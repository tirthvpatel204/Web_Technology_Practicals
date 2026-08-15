"use strict";

/* ================= ELEMENTS ================= */

const form = document.getElementById("registrationForm");

const nameInput = document.getElementById("name");
const emailInput = document.getElementById("email");
const mobileInput = document.getElementById("mobile");
const courseInput = document.getElementById("course");
const yearInput = document.getElementById("year");
const passwordInput = document.getElementById("password");
const confirmPasswordInput = document.getElementById("confirmPassword");
const captchaInput = document.getElementById("captchaInput");
const termsInput = document.getElementById("terms");

const strengthFill = document.getElementById("strengthFill");
const strengthText = document.getElementById("strengthText");

const successMessage = document.getElementById("successMessage");

let captchaCode = "";

/* ================= REGEX ================= */

const patterns = {

    name: /^[A-Za-zÀ-ÿ]+(?:[ '-][A-Za-zÀ-ÿ]+){1,49}$/,

    email: /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/,

    mobile: /^[6-9]\d{9}$/,

    passwordUpper: /[A-Z]/,

    passwordLower: /[a-z]/,

    passwordNumber: /[0-9]/,

    passwordSpecial: /[^A-Za-z0-9]/

};

/* ================= ERROR HANDLING ================= */

function showError(input, errorId, message) {

    const errorElement = document.getElementById(errorId);

    if (errorElement) {
        errorElement.textContent = message;
    }

    if (input) {

        const wrapper = input.closest(".input-wrapper");

        if (wrapper) {
            wrapper.classList.add("invalid");
            wrapper.classList.remove("valid");
        }

        input.setAttribute("aria-invalid", "true");
    }
}


function showSuccess(input, errorId) {

    const errorElement = document.getElementById(errorId);

    if (errorElement) {
        errorElement.textContent = "";
    }

    if (input) {

        const wrapper = input.closest(".input-wrapper");

        if (wrapper) {
            wrapper.classList.remove("invalid");
            wrapper.classList.add("valid");
        }

        input.setAttribute("aria-invalid", "false");
    }
}


function clearValidation(input, errorId) {

    const errorElement = document.getElementById(errorId);

    if (errorElement) {
        errorElement.textContent = "";
    }

    if (input) {

        const wrapper = input.closest(".input-wrapper");

        if (wrapper) {
            wrapper.classList.remove("invalid", "valid");
        }

        input.removeAttribute("aria-invalid");
    }
}

/* ================= NAME VALIDATION ================= */

function validateName() {

    const value = nameInput.value.trim();

    if (value === "") {

        showError(
            nameInput,
            "nameError",
            "Please enter your full name."
        );

        return false;
    }

    if (value.length < 2) {

        showError(
            nameInput,
            "nameError",
            "Name must contain at least 2 characters."
        );

        return false;
    }

    if (!patterns.name.test(value)) {

        showError(
            nameInput,
            "nameError",
            "Please enter a valid name using letters only."
        );

        return false;
    }

    showSuccess(nameInput, "nameError");

    return true;
}

/* ================= EMAIL VALIDATION ================= */

function validateEmail() {

    const value = emailInput.value.trim();

    if (value === "") {

        showError(
            emailInput,
            "emailError",
            "Please enter your email address."
        );

        return false;
    }

    if (!patterns.email.test(value)) {

        showError(
            emailInput,
            "emailError",
            "Please enter a valid email address."
        );

        return false;
    }

    showSuccess(emailInput, "emailError");

    return true;
}

/* ================= MOBILE VALIDATION ================= */

function validateMobile() {

    const value = mobileInput.value.trim();

    if (value === "") {

        showError(
            mobileInput,
            "mobileError",
            "Please enter your mobile number."
        );

        return false;
    }

    if (!patterns.mobile.test(value)) {

        showError(
            mobileInput,
            "mobileError",
            "Enter a valid 10-digit Indian mobile number."
        );

        return false;
    }

    showSuccess(mobileInput, "mobileError");

    return true;
}

/* ================= COURSE ================= */

function validateCourse() {

    if (courseInput.value === "") {

        showError(
            courseInput,
            "courseError",
            "Please select your course."
        );

        return false;
    }

    showSuccess(courseInput, "courseError");

    return true;
}

/* ================= YEAR ================= */

function validateYear() {

    if (yearInput.value === "") {

        showError(
            yearInput,
            "yearError",
            "Please select your current year."
        );

        return false;
    }

    showSuccess(yearInput, "yearError");

    return true;
}

/* ================= GENDER ================= */

function validateGender() {

    const selectedGender =
        document.querySelector('input[name="gender"]:checked');

    const errorElement =
        document.getElementById("genderError");

    if (!selectedGender) {

        errorElement.textContent =
            "Please select your gender.";

        return false;
    }

    errorElement.textContent = "";

    return true;
}

/* ================= PASSWORD STRENGTH ================= */

function checkPasswordStrength() {

    const password = passwordInput.value;

    const rules = {

        length: password.length >= 8,

        upper: patterns.passwordUpper.test(password),

        lower: patterns.passwordLower.test(password),

        number: patterns.passwordNumber.test(password),

        special: patterns.passwordSpecial.test(password)

    };

    document.getElementById("ruleLength")
        .classList.toggle("valid", rules.length);

    document.getElementById("ruleUpper")
        .classList.toggle("valid", rules.upper);

    document.getElementById("ruleLower")
        .classList.toggle("valid", rules.lower);

    document.getElementById("ruleNumber")
        .classList.toggle("valid", rules.number);

    document.getElementById("ruleSpecial")
        .classList.toggle("valid", rules.special);


    const score = Object.values(rules)
        .filter(Boolean)
        .length;


    if (password.length === 0) {

        strengthFill.style.width = "0%";
        strengthText.textContent = "Password strength";

        return 0;
    }


    if (score <= 2) {

        strengthFill.style.width = "25%";
        strengthText.textContent = "Weak";

    } else if (score === 3) {

        strengthFill.style.width = "50%";
        strengthText.textContent = "Fair";

    } else if (score === 4) {

        strengthFill.style.width = "75%";
        strengthText.textContent = "Strong";

    } else {

        strengthFill.style.width = "100%";
        strengthText.textContent = "Very Strong";
    }

    return score;
}

/* ================= PASSWORD VALIDATION ================= */

function validatePassword() {

    const password = passwordInput.value;

    if (password === "") {

        showError(
            passwordInput,
            "passwordError",
            "Please create a password."
        );

        return false;
    }

    const score = checkPasswordStrength();

    if (score < 4) {

        showError(
            passwordInput,
            "passwordError",
            "Password must be at least 8 characters with uppercase, lowercase, number and special character."
        );

        return false;
    }

    showSuccess(passwordInput, "passwordError");

    return true;
}

/* ================= CONFIRM PASSWORD ================= */

function validateConfirmPassword() {

    const password = passwordInput.value;

    const confirmPassword =
        confirmPasswordInput.value;

    if (confirmPassword === "") {

        showError(
            confirmPasswordInput,
            "confirmPasswordError",
            "Please confirm your password."
        );

        return false;
    }

    if (password !== confirmPassword) {

        showError(
            confirmPasswordInput,
            "confirmPasswordError",
            "Passwords do not match."
        );

        return false;
    }

    showSuccess(
        confirmPasswordInput,
        "confirmPasswordError"
    );

    return true;
}

/* ================= CAPTCHA ================= */

function generateCaptcha() {

    const characters =
        "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";

    captchaCode = "";

    for (let i = 0; i < 6; i++) {

        captchaCode +=
            characters.charAt(
                Math.floor(
                    Math.random() * characters.length
                )
            );
    }

    drawCaptcha();
}


function drawCaptcha() {

    const canvas =
        document.getElementById("captchaCanvas");

    const ctx = canvas.getContext("2d");

    ctx.clearRect(
        0,
        0,
        canvas.width,
        canvas.height
    );

    /* Background */

    ctx.fillStyle = "#10121e";

    ctx.fillRect(
        0,
        0,
        canvas.width,
        canvas.height
    );


    /* Noise Lines */

    for (let i = 0; i < 7; i++) {

        ctx.strokeStyle =
            "rgba(124,92,255,0.35)";

        ctx.beginPath();

        ctx.moveTo(
            Math.random() * canvas.width,
            Math.random() * canvas.height
        );

        ctx.lineTo(
            Math.random() * canvas.width,
            Math.random() * canvas.height
        );

        ctx.stroke();
    }


    /* CAPTCHA Text */

    ctx.font = "bold 28px Arial";
    ctx.textBaseline = "middle";

    for (let i = 0; i < captchaCode.length; i++) {

        ctx.save();

        ctx.translate(
            25 + i * 25,
            28
        );

        ctx.rotate(
            (Math.random() - 0.5) * 0.4
        );

        ctx.fillStyle =
            i % 2 === 0
                ? "#9c8cff"
                : "#00d9ff";

        ctx.fillText(
            captchaCode[i],
            0,
            0
        );

        ctx.restore();
    }
}


function validateCaptcha() {

    const value =
        captchaInput.value.trim().toUpperCase();

    if (value === "") {

        showError(
            captchaInput,
            "captchaError",
            "Please enter the CAPTCHA."
        );

        return false;
    }

    if (value !== captchaCode) {

        showError(
            captchaInput,
            "captchaError",
            "Incorrect CAPTCHA. Please try again."
        );

        return false;
    }

    showSuccess(
        captchaInput,
        "captchaError"
    );

    return true;
}

/* ================= TERMS ================= */

function validateTerms() {

    const errorElement =
        document.getElementById("termsError");

    if (!termsInput.checked) {

        errorElement.textContent =
            "You must accept the Terms & Conditions.";

        return false;
    }

    errorElement.textContent = "";

    return true;
}

/* ================= PASSWORD TOGGLE ================= */

document.querySelectorAll(".password-toggle")
    .forEach(button => {

        button.addEventListener("click", function () {

            const targetId =
                this.dataset.target;

            const input =
                document.getElementById(targetId);

            if (input.type === "password") {

                input.type = "text";
                this.textContent = "Hide";
                this.setAttribute(
                    "aria-label",
                    "Hide password"
                );

            } else {

                input.type = "password";
                this.textContent = "Show";
                this.setAttribute(
                    "aria-label",
                    "Show password"
                );
            }

        });

    });

/* ================= REAL-TIME VALIDATION ================= */

nameInput.addEventListener("keyup", validateName);

emailInput.addEventListener("keyup", validateEmail);

mobileInput.addEventListener("keyup", validateMobile);

courseInput.addEventListener("change", validateCourse);

yearInput.addEventListener("change", validateYear);

passwordInput.addEventListener("keyup", function () {

    checkPasswordStrength();

    if (passwordInput.value.length > 0) {
        validatePassword();
    }

    if (confirmPasswordInput.value.length > 0) {
        validateConfirmPassword();
    }

});

confirmPasswordInput.addEventListener(
    "keyup",
    validateConfirmPassword
);

captchaInput.addEventListener(
    "keyup",
    validateCaptcha
);

termsInput.addEventListener(
    "change",
    validateTerms
);

document
    .querySelectorAll('input[name="gender"]')
    .forEach(radio => {

        radio.addEventListener(
            "change",
            validateGender
        );

    });

/* ================= REFRESH CAPTCHA ================= */

document
    .getElementById("refreshCaptcha")
    .addEventListener("click", function () {

        generateCaptcha();

        captchaInput.value = "";

        clearValidation(
            captchaInput,
            "captchaError"
        );

        captchaInput.focus();

    });

/* ================= FORM SUBMISSION ================= */

form.addEventListener("submit", function (event) {

    event.preventDefault();

    successMessage.classList.remove("show");


    const isValid = [

        validateName(),
        validateEmail(),
        validateMobile(),
        validateCourse(),
        validateYear(),
        validateGender(),
        validatePassword(),
        validateConfirmPassword(),
        validateCaptcha(),
        validateTerms()

    ].every(Boolean);


    if (!isValid) {

        const firstInvalid =
            form.querySelector(
                '[aria-invalid="true"]'
            );

        if (firstInvalid) {
            firstInvalid.focus();
        }

        return;
    }


    /* Successful Registration */

    successMessage.classList.add("show");

    form.scrollIntoView({
        behavior: "smooth",
        block: "center"
    });

});

/* ================= CUSTOM CURSOR ================= */

const cursorDot =
    document.querySelector(".cursor-dot");

const cursorRing =
    document.querySelector(".cursor-ring");

let mouseX = 0;
let mouseY = 0;

let ringX = 0;
let ringY = 0;


document.addEventListener("mousemove", function (event) {

    mouseX = event.clientX;
    mouseY = event.clientY;

    cursorDot.style.left =
        mouseX + "px";

    cursorDot.style.top =
        mouseY + "px";

});


function animateCursor() {

    ringX +=
        (mouseX - ringX) * 0.12;

    ringY +=
        (mouseY - ringY) * 0.12;

    cursorRing.style.left =
        ringX + "px";

    cursorRing.style.top =
        ringY + "px";

    requestAnimationFrame(
        animateCursor
    );
}

animateCursor();


/* Cursor Hover Effect */

document
    .querySelectorAll(
        "button, input, select, label, a"
    )
    .forEach(element => {

        element.addEventListener(
            "mouseenter",
            () => {
                cursorRing.classList.add("hover");
            }
        );

        element.addEventListener(
            "mouseleave",
            () => {
                cursorRing.classList.remove("hover");
            }
        );

    });

/* ================= INITIALIZE ================= */

generateCaptcha();