const form = document.getElementById("registrationForm");

const nameInput = document.getElementById("name");
const emailInput = document.getElementById("email");
const mobileInput = document.getElementById("mobile");
const courseInput = document.getElementById("course");
const yearInput = document.getElementById("year");
const passwordInput = document.getElementById("password");
const confirmPasswordInput =
    document.getElementById("confirmPassword");

const termsInput =
    document.getElementById("terms");

const progressBar =
    document.getElementById("progressBar");

const strengthFill =
    document.getElementById("strengthFill");

const strengthText =
    document.getElementById("strengthText");

const successMessage =
    document.getElementById("successMessage");


/* =========================================
   REGEX
========================================= */

const nameRegex =
    /^[A-Za-z ]{2,50}$/;

const emailRegex =
    /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

const mobileRegex =
    /^[6-9][0-9]{9}$/;

const passwordRegex =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;


/* =========================================
   ERROR / SUCCESS
========================================= */

function showError(
    input,
    errorId,
    message
) {

    const error =
        document.getElementById(errorId);

    error.textContent = message;

    const wrapper =
        input.closest(".input-wrapper");

    if (wrapper) {

        wrapper.classList.remove("valid");

        wrapper.classList.add("invalid");
    }
}


function showSuccess(
    input,
    errorId
) {

    const error =
        document.getElementById(errorId);

    error.textContent = "";

    const wrapper =
        input.closest(".input-wrapper");

    if (wrapper) {

        wrapper.classList.remove("invalid");

        wrapper.classList.add("valid");
    }
}


function clearValidation(
    input,
    errorId
) {

    const error =
        document.getElementById(errorId);

    error.textContent = "";

    const wrapper =
        input.closest(".input-wrapper");

    if (wrapper) {

        wrapper.classList.remove("valid");

        wrapper.classList.remove("invalid");
    }
}


/* =========================================
   NAME
========================================= */

function validateName() {

    const value =
        nameInput.value.trim();

    if (value === "") {

        showError(
            nameInput,
            "nameError",
            "Please enter your full name."
        );

        return false;
    }

    if (!nameRegex.test(value)) {

        showError(
            nameInput,
            "nameError",
            "Name should contain only letters and spaces."
        );

        return false;
    }

    showSuccess(
        nameInput,
        "nameError"
    );

    return true;
}


/* =========================================
   EMAIL
========================================= */

function validateEmail() {

    const value =
        emailInput.value.trim();

    if (value === "") {

        showError(
            emailInput,
            "emailError",
            "Please enter your email address."
        );

        return false;
    }

    if (!emailRegex.test(value)) {

        showError(
            emailInput,
            "emailError",
            "Please enter a valid email address."
        );

        return false;
    }

    showSuccess(
        emailInput,
        "emailError"
    );

    return true;
}


/* =========================================
   MOBILE
========================================= */

function validateMobile() {

    const value =
        mobileInput.value.trim();

    if (value === "") {

        showError(
            mobileInput,
            "mobileError",
            "Please enter your mobile number."
        );

        return false;
    }

    if (!mobileRegex.test(value)) {

        showError(
            mobileInput,
            "mobileError",
            "Enter a valid 10-digit Indian mobile number."
        );

        return false;
    }

    showSuccess(
        mobileInput,
        "mobileError"
    );

    return true;
}


/* =========================================
   COURSE
========================================= */

function validateCourse() {

    if (courseInput.value === "") {

        showError(
            courseInput,
            "courseError",
            "Please select your course."
        );

        return false;
    }

    showSuccess(
        courseInput,
        "courseError"
    );

    return true;
}


/* =========================================
   YEAR
========================================= */

function validateYear() {

    if (yearInput.value === "") {

        showError(
            yearInput,
            "yearError",
            "Please select your academic year."
        );

        return false;
    }

    showSuccess(
        yearInput,
        "yearError"
    );

    return true;
}


/* =========================================
   GENDER
========================================= */

function validateGender() {

    const selectedGender =
        document.querySelector(
            'input[name="gender"]:checked'
        );

    const error =
        document.getElementById(
            "genderError"
        );

    if (!selectedGender) {

        error.textContent =
            "Please select your gender.";

        return false;
    }

    error.textContent = "";

    return true;
}


/* =========================================
   PASSWORD STRENGTH
========================================= */

function checkPasswordStrength() {

    const password =
        passwordInput.value;

    let score = 0;

    if (password.length >= 8) {
        score++;
    }

    if (/[a-z]/.test(password)) {
        score++;
    }

    if (/[A-Z]/.test(password)) {
        score++;
    }

    if (/[0-9]/.test(password)) {
        score++;
    }

    if (/[@$!%*?&]/.test(password)) {
        score++;
    }

    const percentage =
        score * 20;

    strengthFill.style.width =
        percentage + "%";


    if (password.length === 0) {

        strengthText.textContent =
            "Password strength";

    } else if (score <= 2) {

        strengthText.textContent =
            "Weak";

    } else if (score === 3) {

        strengthText.textContent =
            "Medium";

    } else if (score === 4) {

        strengthText.textContent =
            "Strong";

    } else {

        strengthText.textContent =
            "Very Strong";
    }
}


/* =========================================
   PASSWORD
========================================= */

function validatePassword() {

    const value =
        passwordInput.value;

    if (value === "") {

        showError(
            passwordInput,
            "passwordError",
            "Please create a password."
        );

        return false;
    }

    if (!passwordRegex.test(value)) {

        showError(
            passwordInput,
            "passwordError",
            "Password must contain 8+ characters, uppercase, lowercase, number and special character."
        );

        return false;
    }

    showSuccess(
        passwordInput,
        "passwordError"
    );

    return true;
}


/* =========================================
   CONFIRM PASSWORD
========================================= */

function validateConfirmPassword() {

    const password =
        passwordInput.value;

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


/* =========================================
   TERMS
========================================= */

function validateTerms() {

    const error =
        document.getElementById(
            "termsError"
        );

    if (!termsInput.checked) {

        error.textContent =
            "You must accept the Terms & Conditions.";

        return false;
    }

    error.textContent = "";

    return true;
}


/* =========================================
   PROGRESS BAR
========================================= */

function updateProgress() {

    let completed = 0;

    const total = 9;


    if (nameInput.value.trim() !== "") {
        completed++;
    }

    if (emailRegex.test(
        emailInput.value.trim()
    )) {
        completed++;
    }

    if (mobileRegex.test(
        mobileInput.value.trim()
    )) {
        completed++;
    }

    if (courseInput.value !== "") {
        completed++;
    }

    if (yearInput.value !== "") {
        completed++;
    }

    if (
        document.querySelector(
            'input[name="gender"]:checked'
        )
    ) {
        completed++;
    }

    if (
        passwordRegex.test(
            passwordInput.value
        )
    ) {
        completed++;
    }

    if (
        confirmPasswordInput.value !== "" &&
        confirmPasswordInput.value ===
        passwordInput.value
    ) {
        completed++;
    }

    if (termsInput.checked) {
        completed++;
    }


    const percentage =
        (completed / total) * 100;

    progressBar.style.width =
        percentage + "%";
}


/* =========================================
   REAL-TIME VALIDATION
========================================= */

nameInput.addEventListener(
    "input",
    function () {

        validateName();

        updateProgress();
    }
);


emailInput.addEventListener(
    "input",
    function () {

        validateEmail();

        updateProgress();
    }
);


mobileInput.addEventListener(
    "input",
    function () {

        mobileInput.value =
            mobileInput.value.replace(
                /\D/g,
                ""
            );

        validateMobile();

        updateProgress();
    }
);


courseInput.addEventListener(
    "change",
    function () {

        validateCourse();

        updateProgress();
    }
);


yearInput.addEventListener(
    "change",
    function () {

        validateYear();

        updateProgress();
    }
);


passwordInput.addEventListener(
    "input",
    function () {

        checkPasswordStrength();

        validatePassword();

        if (
            confirmPasswordInput.value !== ""
        ) {

            validateConfirmPassword();
        }

        updateProgress();
    }
);


confirmPasswordInput.addEventListener(
    "input",
    function () {

        validateConfirmPassword();

        updateProgress();
    }
);


document.querySelectorAll(
    'input[name="gender"]'
).forEach(
    function (radio) {

        radio.addEventListener(
            "change",
            function () {

                validateGender();

                updateProgress();
            }
        );

    }
);


termsInput.addEventListener(
    "change",
    function () {

        validateTerms();

        updateProgress();
    }
);


/* =========================================
   SHOW / HIDE PASSWORD
========================================= */

function togglePassword(
    input,
    button
) {

    if (input.type === "password") {

        input.type = "text";

        button.textContent = "🙈";

        button.setAttribute(
            "aria-label",
            "Hide password"
        );

    } else {

        input.type = "password";

        button.textContent = "👁";

        button.setAttribute(
            "aria-label",
            "Show password"
        );
    }
}


document.getElementById(
    "passwordToggle"
).addEventListener(
    "click",
    function () {

        togglePassword(
            passwordInput,
            this
        );
    }
);


document.getElementById(
    "confirmToggle"
).addEventListener(
    "click",
    function () {

        togglePassword(
            confirmPasswordInput,
            this
        );
    }
);


/* =========================================
   FORM SUBMISSION
========================================= */

form.addEventListener(
    "submit",
    function (event) {

        event.preventDefault();


        const validName =
            validateName();

        const validEmail =
            validateEmail();

        const validMobile =
            validateMobile();

        const validCourse =
            validateCourse();

        const validYear =
            validateYear();

        const validGender =
            validateGender();

        const validPassword =
            validatePassword();

        const validConfirmPassword =
            validateConfirmPassword();

        const validTerms =
            validateTerms();


        updateProgress();


        if (
            validName &&
            validEmail &&
            validMobile &&
            validCourse &&
            validYear &&
            validGender &&
            validPassword &&
            validConfirmPassword &&
            validTerms
        ) {

            successMessage.classList.add(
                "show"
            );


            successMessage.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });


            setTimeout(
                function () {

                    form.reset();


                    clearValidation(
                        nameInput,
                        "nameError"
                    );

                    clearValidation(
                        emailInput,
                        "emailError"
                    );

                    clearValidation(
                        mobileInput,
                        "mobileError"
                    );

                    clearValidation(
                        courseInput,
                        "courseError"
                    );

                    clearValidation(
                        yearInput,
                        "yearError"
                    );

                    clearValidation(
                        passwordInput,
                        "passwordError"
                    );

                    clearValidation(
                        confirmPasswordInput,
                        "confirmPasswordError"
                    );


                    document.getElementById(
                        "genderError"
                    ).textContent = "";


                    document.getElementById(
                        "termsError"
                    ).textContent = "";


                    strengthFill.style.width =
                        "0%";


                    strengthText.textContent =
                        "Password strength";


                    progressBar.style.width =
                        "0%";


                    successMessage.classList.remove(
                        "show"
                    );

                },
                4000
            );

        } else {

            const firstError =
                document.querySelector(
                    ".input-wrapper.invalid input, .input-wrapper.invalid select"
                );


            if (firstError) {

                firstError.focus();
            }
        }
    }
);


/* =========================================
   PREMIUM CUSTOM CURSOR
========================================= */

const cursorDot =
    document.querySelector(
        ".cursor-dot"
    );

const cursorRing =
    document.querySelector(
        ".cursor-ring"
    );


if (
    cursorDot &&
    cursorRing &&
    window.matchMedia(
        "(min-width: 769px)"
    ).matches
) {

    let mouseX =
        window.innerWidth / 2;

    let mouseY =
        window.innerHeight / 2;

    let ringX = mouseX;
    let ringY = mouseY;


    /* Mouse position */

    document.addEventListener(
        "mousemove",
        function (event) {

            mouseX =
                event.clientX;

            mouseY =
                event.clientY;


            cursorDot.style.transform =
                `translate3d(
                    ${mouseX}px,
                    ${mouseY}px,
                    0
                ) translate(-50%, -50%)`;
        }
    );


    /* Smooth ring movement */

    function animateCursor() {

        ringX +=
            (mouseX - ringX) * 0.15;

        ringY +=
            (mouseY - ringY) * 0.15;


        cursorRing.style.transform =
            `translate3d(
                ${ringX}px,
                ${ringY}px,
                0
            ) translate(-50%, -50%)`;


        requestAnimationFrame(
            animateCursor
        );
    }


    animateCursor();


    /* =====================================
       HOVER EFFECT
    ===================================== */

    const interactiveElements =
        document.querySelectorAll(
            "button, a, input, select, label, .gender-card"
        );


    interactiveElements.forEach(
        function (element) {

            element.addEventListener(
                "mouseenter",
                function () {

                    cursorRing.classList.add(
                        "hover"
                    );

                    cursorDot.classList.add(
                        "hover"
                    );
                }
            );


            element.addEventListener(
                "mouseleave",
                function () {

                    cursorRing.classList.remove(
                        "hover"
                    );

                    cursorDot.classList.remove(
                        "hover"
                    );
                }
            );

        }
    );


    /* =====================================
       INPUT CURSOR EFFECT
    ===================================== */

    const textInputs =
        document.querySelectorAll(
            "input[type='text'], input[type='email'], input[type='tel'], input[type='password']"
        );


    textInputs.forEach(
        function (input) {

            input.addEventListener(
                "mouseenter",
                function () {

                    cursorRing.classList.add(
                        "text"
                    );
                }
            );


            input.addEventListener(
                "mouseleave",
                function () {

                    cursorRing.classList.remove(
                        "text"
                    );
                }
            );

        }
    );


    /* =====================================
       CLICK EFFECT
    ===================================== */

    document.addEventListener(
        "mousedown",
        function () {

            cursorRing.classList.add(
                "click"
            );
        }
    );


    document.addEventListener(
        "mouseup",
        function () {

            cursorRing.classList.remove(
                "click"
            );
        }
    );


    /* =====================================
       WINDOW EXIT
    ===================================== */

    document.addEventListener(
        "mouseleave",
        function () {

            cursorDot.style.opacity =
                "0";

            cursorRing.style.opacity =
                "0";
        }
    );


    document.addEventListener(
        "mouseenter",
        function () {

            cursorDot.style.opacity =
                "1";

            cursorRing.style.opacity =
                "1";
        }
    );
}