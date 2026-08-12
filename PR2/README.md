# 🌐 Practical 2 – Semantic HTML5 Pages with Accessibility-Ready Structure

## 📌 Practical Overview

This practical focuses on creating the static HTML foundation of the **StudentHub Portal** using semantic HTML5 elements and accessibility-friendly structures.

At least 10 interconnected pages are created with a consistent navigation system, meaningful content hierarchy, accessible forms, and appropriate semantic elements.

---

## 🎯 Problem Definition

Develop static HTML5 skeletons for the StudentHub portal using semantic and accessible page structures.

The project includes:

* Semantic HTML5
* Consistent navigation
* Accessible forms
* Proper heading hierarchy
* Image alternative text
* Internal page linking
* Consistent header and footer
* Keyboard-friendly structure

---

## 📄 Pages Implemented

The StudentHub portal includes:

1. Home
2. About
3. Register
4. Login
5. Dashboard
6. Events
7. Profile
8. Contact
9. Admin
10. FAQ
11. Feedback

---

## 🛠️ Technologies Used

| Technology               | Purpose                |
| ------------------------ | ---------------------- |
| HTML5                    | Page structure         |
| VS Code                  | Development            |
| Browser DevTools         | Testing                |
| HTML Accessibility Tools | Accessibility checking |

---

## 🧱 Semantic HTML Structure

The pages use semantic HTML5 elements such as:

```html
<header>
<nav>
<main>
<section>
<article>
<aside>
<footer>
```

Example structure:

```text
<body>
│
├── Header
│   └── Navigation
│
├── Main
│   ├── Section
│   ├── Article
│   └── Aside
│
└── Footer
```

---

## 📁 Project Structure

```text
Practical-2/
│
├── index.html
├── about.html
├── register.html
├── login.html
├── dashboard.html
├── events.html
├── profile.html
├── contact.html
├── admin.html
├── faq.html
├── feedback.html
│
└── README.md
```

---

## 🔗 Page Navigation

All pages are connected using HTML anchor elements.

Example:

```html
<nav>
    <a href="index.html">Home</a>
    <a href="about.html">About</a>
    <a href="events.html">Events</a>
    <a href="contact.html">Contact</a>
</nav>
```

This provides consistent navigation throughout the StudentHub portal.

---

## ♿ Accessibility Features

### 1. Semantic Elements

Meaningful HTML elements are used to describe the page structure.

### 2. Form Labels

Each form input has an associated label.

```html
<label for="email">
    Email
</label>

<input
    type="email"
    id="email"
    name="email">
```

### 3. Heading Hierarchy

Headings follow a logical structure:

```text
h1
 ├── h2
 │    └── h3
 └── h2
```

### 4. Image Alt Text

Images include descriptive alternative text.

```html
<img
    src="event.jpg"
    alt="Students attending a campus event">
```

### 5. Navigation Links

Navigation links have meaningful text and point to valid pages.

---

## 🍞 Intermediate Extension – Breadcrumb Navigation

Breadcrumb navigation can be added to help users understand their current location.

Example:

```text
Home → Events → Event Details
```

HTML example:

```html
<nav aria-label="Breadcrumb">

    <a href="index.html">
        Home
    </a>

    <span> → </span>

    <a href="events.html">
        Events
    </a>

</nav>
```

---

## ⌨️ Advanced Extension – Keyboard Accessibility

A skip-to-content link can be provided for keyboard users.

```html
<a href="#main-content"
   class="skip-link">
    Skip to main content
</a>
```

The main content can then be identified as:

```html
<main id="main-content">
```

This allows keyboard users to bypass repeated navigation.

---

## 🧪 Accessibility Checklist

| Check                       | Status |
| --------------------------- | ------ |
| Semantic HTML5 elements     | ✅      |
| Proper heading hierarchy    | ✅      |
| Form labels                 | ✅      |
| Image alt attributes        | ✅      |
| Navigation links            | ✅      |
| Consistent page structure   | ✅      |
| Keyboard-friendly structure | ✅      |
| Skip-to-content             | ✅      |
| Breadcrumb navigation       | ✅      |

---

## 🖼️ Screenshots

Add screenshots of the completed pages.

Recommended structure:

```text
screenshots/
│
├── home.png
├── about.png
├── register.png
├── login.png
├── dashboard.png
├── events.png
├── profile.png
├── contact.png
├── admin.png
├── faq.png
└── feedback.png
```

---

## ▶️ How to Run

1. Open the project folder in VS Code.
2. Open `index.html`.
3. Run using a browser or Live Server.
4. Test all navigation links.
5. Check forms and accessibility features.

---

## 🧪 Testing

| Test           | Expected Result      | Status |
| -------------- | -------------------- | ------ |
| Home link      | Opens Home page      | ✅      |
| About link     | Opens About page     | ✅      |
| Register link  | Opens Register page  | ✅      |
| Login link     | Opens Login page     | ✅      |
| Dashboard link | Opens Dashboard page | ✅      |
| Events link    | Opens Events page    | ✅      |
| Profile link   | Opens Profile page   | ✅      |
| Contact link   | Opens Contact page   | ✅      |
| FAQ link       | Opens FAQ page       | ✅      |
| Feedback link  | Opens Feedback page  | ✅      |
| Form labels    | Properly associated  | ✅      |
| Images         | Alt text available   | ✅      |

---

## 🎓 Learning Outcomes

After completing this practical, I learned:

* Semantic HTML5 structure
* Accessible web page design
* Form accessibility
* Proper heading hierarchy
* Internal page linking
* Navigation structure
* Content organization
* Basic keyboard accessibility

---

## 📚 Practical Details

**Practical:** 02
**Project:** StudentHub Portal
**Topic:** Semantic HTML5 Pages with Accessibility-Ready Structure
**CO Mapping:** CO1, CO2
**Tools:** VS Code, HTML5, Browser DevTools

---

## 👨‍💻 Author

**Tirth**
