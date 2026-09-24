let form = document.getElementById("form");

form.onsubmit = function(e) {

    e.preventDefault();

    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let mobile = document.getElementById("mobile").value;
    let pass = document.getElementById("pass").value;
    let cpass = document.getElementById("cpass").value;
    let course = document.getElementById("course").value;
    let year = document.getElementById("year").value;
    let gender = document.querySelector('input[name="gender"]:checked');
    let terms = document.getElementById("terms").checked;

    let ok = true;

    let nameR = /^[A-Za-z ]+$/;
    let emailR = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let mobileR = /^[6-9][0-9]{9}$/;
    let passR = /^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[@$!%*?&]).{8,}$/;

    document.querySelectorAll("span").forEach(x => x.innerHTML = "");

    if (!nameR.test(name)) {
        nameErr.innerHTML = "Enter valid name";
        ok = false;
    }

    if (!emailR.test(email)) {
        emailErr.innerHTML = "Enter valid email";
        ok = false;
    }

    if (!mobileR.test(mobile)) {
        mobileErr.innerHTML = "Enter valid mobile";
        ok = false;
    }

    if (!passR.test(pass)) {
        passErr.innerHTML = "Password must be 8+ characters";
        ok = false;
    }

    if (pass != cpass) {
        cpassErr.innerHTML = "Password not matched";
        ok = false;
    }

    if (course == "") {
        courseErr.innerHTML = "Select course";
        ok = false;
    }

    if (year == "") {
        yearErr.innerHTML = "Select year";
        ok = false;
    }

    if (!gender) {
        genderErr.innerHTML = "Select gender";
        ok = false;
    }

    if (!terms) {
        termsErr.innerHTML = "Accept terms";
        ok = false;
    }

    if (ok)
        alert("Registration Successful!");
};