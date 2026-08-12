// =====================================================
// DOM SELECTION
// =====================================================

const body = document.body;

const themeButton =
    document.getElementById("themeButton");

const menuButton =
    document.getElementById("menuButton");

const navbar =
    document.getElementById("navbar");

const notification =
    document.getElementById("notification");

const closeNotification =
    document.getElementById("closeNotification");

const modal =
    document.getElementById("modal");

const openModal =
    document.getElementById("openModal");

const closeModal =
    document.getElementById("closeModal");

const modalOk =
    document.getElementById("modalOk");

const toast =
    document.getElementById("toast");

const toastMessage =
    document.getElementById("toastMessage");


// =====================================================
// TOAST FUNCTION
// =====================================================

function showToast(message) {

    toastMessage.textContent = message;

    toast.classList.add("show");

    setTimeout(function () {

        toast.classList.remove("show");

    }, 3000);
}


// =====================================================
// THEME + LOCAL STORAGE
// =====================================================

const savedTheme =
    localStorage.getItem("theme");


if (savedTheme === "dark") {

    body.classList.add("dark");

    themeButton.textContent = "☀️";

}


themeButton.addEventListener("click", function () {

    body.classList.toggle("dark");


    if (body.classList.contains("dark")) {

        localStorage.setItem("theme", "dark");

        themeButton.textContent = "☀️";

        showToast("Dark Mode Enabled");

    } else {

        localStorage.setItem("theme", "light");

        themeButton.textContent = "🌙";

        showToast("Light Mode Enabled");

    }

});


// =====================================================
// HAMBURGER MENU
// =====================================================

menuButton.addEventListener("click", function () {

    const isOpen =
        navbar.classList.toggle("show");

    menuButton.setAttribute(
        "aria-expanded",
        isOpen
    );

});


// Close menu when navigation link clicked

const navLinks =
    document.querySelectorAll(".nav-link");


navLinks.forEach(function (link) {

    link.addEventListener("click", function () {

        navbar.classList.remove("show");

        menuButton.setAttribute(
            "aria-expanded",
            "false"
        );

    });

});


// Close menu when clicking outside

document.addEventListener("click", function (event) {

    if (
        !navbar.contains(event.target) &&
        !menuButton.contains(event.target)
    ) {

        navbar.classList.remove("show");

        menuButton.setAttribute(
            "aria-expanded",
            "false"
        );

    }

});


// =====================================================
// NOTIFICATION
// =====================================================

closeNotification.addEventListener(
    "click",
    function () {

        notification.style.display = "none";

        showToast("Notification Closed");

    }
);


// Automatically hide after 10 seconds

setTimeout(function () {

    notification.style.display = "none";

}, 10000);


// =====================================================
// MODAL
// =====================================================

openModal.addEventListener("click", function () {

    modal.classList.add("show");

    document.body.style.overflow = "hidden";

});


function closeModalFunction() {

    modal.classList.remove("show");

    document.body.style.overflow = "auto";

}


closeModal.addEventListener(
    "click",
    closeModalFunction
);


modalOk.addEventListener(
    "click",
    closeModalFunction
);


// Close when clicking outside modal

modal.addEventListener("click", function (event) {

    if (event.target === modal) {

        closeModalFunction();

    }

});


// =====================================================
// ESCAPE KEY
// =====================================================

document.addEventListener("keydown", function (event) {

    if (event.key === "Escape") {

        closeModalFunction();

        navbar.classList.remove("show");

        menuButton.setAttribute(
            "aria-expanded",
            "false"
        );

    }

});


// =====================================================
// FAQ ACCORDION
// =====================================================

const faqQuestions =
    document.querySelectorAll(".faq-question");


faqQuestions.forEach(function (question) {

    question.addEventListener("click", function () {

        const currentItem =
            question.parentElement;

        const isAlreadyOpen =
            currentItem.classList.contains("open");


        // Close all FAQ items

        document
            .querySelectorAll(".faq-item")
            .forEach(function (item) {

                item.classList.remove("open");

                item
                    .querySelector(".faq-question")
                    .setAttribute(
                        "aria-expanded",
                        "false"
                    );

            });


        // Open selected FAQ

        if (!isAlreadyOpen) {

            currentItem.classList.add("open");

            question.setAttribute(
                "aria-expanded",
                "true"
            );

        }

    });

});


// =====================================================
// SLIDER DATA
// =====================================================

const slides = [

    {
        title: "DOM Manipulation",

        text:
        "JavaScript can select, modify and create HTML elements."
    },

    {
        title: "Event Handling",

        text:
        "Events allow webpages to respond to user actions."
    },

    {
        title: "localStorage",

        text:
        "User preferences can be stored and restored after refresh."
    },

    {
        title: "Accessibility",

        text:
        "Keyboard support and ARIA attributes make interfaces easier to use."
    }

];


let currentSlide = 0;


const slideTitle =
    document.getElementById("slideTitle");

const slideText =
    document.getElementById("slideText");

const indicators =
    document.getElementById("indicators");


// =====================================================
// CREATE SLIDER INDICATORS
// =====================================================

slides.forEach(function (slide, index) {

    const button =
        document.createElement("button");

    button.classList.add("indicator");

    button.setAttribute(
        "aria-label",
        "Go to slide " + (index + 1)
    );


    button.addEventListener("click", function () {

        currentSlide = index;

        showSlide();

    });


    indicators.appendChild(button);

});


// =====================================================
// SHOW SLIDE
// =====================================================

function showSlide() {

    slideTitle.textContent =
        slides[currentSlide].title;

    slideText.textContent =
        slides[currentSlide].text;


    const allIndicators =
        document.querySelectorAll(".indicator");


    allIndicators.forEach(function (indicator) {

        indicator.classList.remove("active");

    });


    allIndicators[currentSlide]
        .classList.add("active");

}


showSlide();


// =====================================================
// NEXT SLIDE
// =====================================================

document
    .getElementById("next")
    .addEventListener("click", function () {

        currentSlide++;

        if (currentSlide >= slides.length) {

            currentSlide = 0;

        }

        showSlide();

    });


// =====================================================
// PREVIOUS SLIDE
// =====================================================

document
    .getElementById("previous")
    .addEventListener("click", function () {

        currentSlide--;

        if (currentSlide < 0) {

            currentSlide = slides.length - 1;

        }

        showSlide();

    });


// =====================================================
// AUTO SLIDER
// =====================================================

setInterval(function () {

    currentSlide++;

    if (currentSlide >= slides.length) {

        currentSlide = 0;

    }

    showSlide();

}, 5000);


// =====================================================
// FORM VALIDATION
// =====================================================

const contactForm =
    document.getElementById("contactForm");


contactForm.addEventListener("submit", function (event) {

    event.preventDefault();


    const name =
        document.getElementById("name").value.trim();

    const email =
        document.getElementById("email").value.trim();

    const message =
        document.getElementById("message").value.trim();


    if (
        name === "" ||
        email === "" ||
        message === ""
    ) {

        showToast(
            "Please fill all fields"
        );

        return;

    }


    showToast(
        "Message submitted successfully!"
    );


    contactForm.reset();

});


// =====================================================
// CURRENT DATE
// =====================================================

const currentDate =
    document.getElementById("currentDate");


const today =
    new Date();


currentDate.textContent =
    today.toLocaleDateString(
        "en-IN",
        {
            day: "numeric",
            month: "long",
            year: "numeric"
        }
    );


// =====================================================
// ACTIVE NAVIGATION
// =====================================================

const sections =
    document.querySelectorAll("main section");


window.addEventListener("scroll", function () {

    let currentSection = "";

    sections.forEach(function (section) {

        const sectionTop =
            section.offsetTop - 150;

        if (window.scrollY >= sectionTop) {

            currentSection =
                section.getAttribute("id");

        }

    });


    navLinks.forEach(function (link) {

        link.classList.remove("active");


        if (
            link.getAttribute("href") ===
            "#" + currentSection
        ) {

            link.classList.add("active");

        }

    });

});