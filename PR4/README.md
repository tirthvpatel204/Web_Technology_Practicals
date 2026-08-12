# 🚀 Practical 4 – JavaScript DOM Manipulation & UI Interactivity

A responsive and interactive web application developed using **HTML5, CSS3, and JavaScript ES6+**.

This practical demonstrates how JavaScript can be used to manipulate the DOM, handle user events, create dynamic UI components, and store user preferences using `localStorage`.

---

## 📌 Objective

The objective of this practical is to enhance a static webpage with dynamic and interactive features using JavaScript.

### Main Features

* 🧩 DOM Manipulation
* ⚡ Event Handling
* ❓ Collapsible FAQ
* 🪟 Modal Popup
* 🎞️ Content Slider
* 🔔 Notification Banner
* ☰ Responsive Hamburger Menu
* 🌙 Light/Dark Theme Switcher
* 💾 Theme Persistence using `localStorage`
* 🔔 Toast Notifications
* 📝 Form Validation
* ⌨️ Keyboard Accessibility
* 📱 Responsive Design
* ✨ CSS Transitions and Animations
* 🔗 Active Navigation Highlighting

---

## 🛠️ Technologies Used

| Technology       | Purpose                                  |
| ---------------- | ---------------------------------------- |
| HTML5            | Webpage structure                        |
| CSS3             | Styling, animation and responsive design |
| JavaScript ES6+  | DOM manipulation and interactivity       |
| localStorage     | Saving user preferences                  |
| Browser DevTools | Testing and debugging                    |

---

## 📂 Project Structure

```text
Practical-4/
│
├── index.html
├── style.css
├── script.js
└── README.md
```

---

## ✨ Features

### 1. DOM Manipulation

JavaScript is used to select and modify HTML elements dynamically.

```javascript
document.getElementById()
document.querySelector()
document.querySelectorAll()
```

---

### 2. Event Handling

User actions are handled using JavaScript event listeners.

```javascript
element.addEventListener("click", function () {
    // Action
});
```

Events implemented include:

* Click
* Scroll
* Keyboard
* Form Submit

---

### 3. Collapsible FAQ

Users can click on a question to open or close its answer.

The FAQ uses:

```javascript
classList.add()
classList.remove()
```

and dynamic DOM manipulation.

---

### 4. Modal Popup

The modal opens when the user clicks **Explore Project**.

It can be closed by:

* Close button
* Clicking outside the modal
* Pressing `Esc`

---

### 5. Content Slider

The slider supports:

* Next slide
* Previous slide
* Slide indicators
* Automatic slide change
* Dynamic slide content

The indicators are also created dynamically using:

```javascript
document.createElement()
```

---

### 6. Notification Banner

A notification banner appears at the top of the page.

It supports:

* Manual close
* Automatic hide after a fixed duration
* Toast feedback

---

### 7. Responsive Hamburger Menu

On mobile devices, the navigation changes into a hamburger menu.

The menu supports:

* Open/close
* Outside-click detection
* `Esc` key closing
* `aria-expanded` accessibility state

---

### 8. Light/Dark Mode

The webpage supports both Light and Dark themes.

The selected theme is saved using `localStorage`.

```javascript
localStorage.setItem("theme", "dark");
```

On page load, the saved preference is restored:

```javascript
localStorage.getItem("theme");
```

Therefore, the selected theme remains after refreshing the webpage.

---

### 9. Toast Notification

Toast messages provide immediate feedback to the user.

Examples:

* Dark Mode Enabled
* Light Mode Enabled
* Notification Closed
* Message submitted successfully

---

### 10. Form Validation

The contact form validates:

* Name
* Email
* Message

JavaScript prevents empty form submission and displays an appropriate notification.

---

### 11. Accessibility

Accessibility features include:

* Semantic HTML
* Form labels
* `aria-label`
* `aria-expanded`
* `aria-modal`
* Keyboard navigation
* `Esc` key support

---

### 12. Responsive Design

The webpage is designed for:

* 💻 Desktop
* 📱 Mobile
* 📲 Tablet

CSS media queries are used to create a responsive layout.

---

## 💾 Advanced Feature – localStorage

The advanced requirement of the practical is implemented using browser `localStorage`.

When the user selects Dark Mode:

```javascript
localStorage.setItem("theme", "dark");
```

When the webpage loads:

```javascript
const savedTheme = localStorage.getItem("theme");
```

If the saved theme is `"dark"`, Dark Mode is automatically restored.

---

## 🧪 Testing

| Test             | Expected Result        | Status |
| ---------------- | ---------------------- | ------ |
| FAQ click        | Answer opens/closes    | ✅      |
| Modal button     | Modal opens            | ✅      |
| Modal close      | Modal closes           | ✅      |
| `Esc` key        | Modal/menu closes      | ✅      |
| Next button      | Next slide appears     | ✅      |
| Previous button  | Previous slide appears | ✅      |
| Theme button     | Theme changes          | ✅      |
| Page refresh     | Saved theme remains    | ✅      |
| Hamburger button | Menu opens/closes      | ✅      |
| Empty form       | Validation message     | ✅      |
| Valid form       | Success notification   | ✅      |
| Mobile screen    | Responsive layout      | ✅      |

---

## ▶️ How to Run

### 1. Clone the repository

```bash
git clone <your-repository-url>
```

### 2. Open the project

Open the project folder in **VS Code**.

### 3. Run the webpage

Open:

```text
index.html
```

You can also use the **Live Server** extension in VS Code.

---

## 📸 Screenshots

Add screenshots of your working project here.

Recommended screenshots:

```text
screenshots/
│
├── home.png
├── features.png
├── slider.png
├── faq.png
├── modal.png
├── dark-mode.png
├── mobile-menu.png
└── contact-form.png
```

### Home Page

*Add screenshot here*

### Dark Mode

*Add screenshot here*

### FAQ

*Add screenshot here*

### Modal Popup

*Add screenshot here*

### Mobile Menu

*Add screenshot here*

---

## 🧠 Key Concepts Demonstrated

```text
DOM Selection
      ↓
DOM Modification
      ↓
Event Handling
      ↓
Class Manipulation
      ↓
Dynamic UI Components
      ↓
localStorage
      ↓
Accessibility
      ↓
Responsive Design
```

---

## 🎓 Learning Outcomes

After completing this practical, I learned how to:

* Manipulate HTML elements using JavaScript.
* Handle different user events.
* Build interactive UI components.
* Dynamically create DOM elements.
* Use CSS classes through JavaScript.
* Store and retrieve data using `localStorage`.
* Implement Dark Mode persistence.
* Create responsive navigation.
* Add keyboard accessibility.
* Perform client-side form validation.
* Test JavaScript using browser DevTools.

---

## 🗣️ Viva Questions

### What is DOM?

DOM stands for **Document Object Model**. It represents an HTML document as a tree of objects that JavaScript can access and modify.

### What is `addEventListener()`?

It is used to attach an event handler to an HTML element.

### What is `classList.toggle()`?

It adds a class if it is absent and removes it if it is already present.

### Why is `localStorage` used?

It stores data in the browser and keeps it available even after refreshing the webpage.

### How does Dark Mode work?

JavaScript toggles a CSS class on the `<body>` and stores the selected theme in `localStorage`.

### Why are ARIA attributes used?

ARIA attributes improve accessibility for users who use assistive technologies.

---

## 📚 Practical Details

**Practical:** 04
**Title:** JavaScript DOM Manipulation, Event Handling, and UI Interactivity
**Level:** Advanced
**Technologies:** HTML5, CSS3, JavaScript ES6+
**CO Mapping:** CO3, CO4

---

## 👨‍💻 Author

**Tirth**

---

## ⭐ Conclusion

This project demonstrates how JavaScript can transform a static webpage into a **dynamic, responsive, accessible, and interactive web application** using DOM manipulation, event handling, animations, and `localStorage`.
