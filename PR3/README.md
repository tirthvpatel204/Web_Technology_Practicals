# 🎨 Practical 3 – Responsive UI Design using CSS Grid, Flexbox & Bootstrap

## 📌 Practical Overview

This practical focuses on designing a responsive and professional user interface for the **StudentHub Portal** using CSS3, Flexbox, CSS Grid, and responsive design techniques.

The layouts are designed using a mobile-first approach so that the webpages work effectively across mobile, tablet, and desktop screen sizes.

---

## 🎯 Problem Definition

Design responsive layouts for the major StudentHub pages using modern CSS layout techniques.

The implementation focuses on:

* CSS Flexbox
* CSS Grid
* Media Queries
* Responsive typography
* Responsive spacing
* Consistent colors
* Readable contrast
* Reusable UI components
* Mobile-first design

Bootstrap can also be used for predefined responsive components.

---

## 📄 Pages

Responsive layouts are implemented for:

1. Home
2. About
3. Registration
4. Dashboard
5. Events

### Intermediate Extension

Additional pages:

6. Contact
7. Feedback

---

## 🛠️ Technologies Used

| Technology       | Purpose                 |
| ---------------- | ----------------------- |
| HTML5            | Page structure          |
| CSS3             | Styling                 |
| Flexbox          | One-dimensional layouts |
| CSS Grid         | Two-dimensional layouts |
| Media Queries    | Responsive behaviour    |
| Bootstrap        | Optional UI framework   |
| VS Code          | Development             |
| Browser DevTools | Responsive testing      |

---

## 📁 Project Structure

```text
Practical-3/
│
├── index.html
├── about.html
├── register.html
├── dashboard.html
├── events.html
├── contact.html
├── feedback.html
│
├── css/
│   └── style.css
│
├── images/
│
└── README.md
```

---

## 📱 Responsive Design

The interface adapts to different screen sizes.

### Mobile

```text
┌──────────────┐
│ Logo    ☰    │
├──────────────┤
│              │
│    Content   │
│              │
├──────────────┤
│    Footer    │
└──────────────┘
```

### Tablet

```text
┌────────────────────┐
│ Logo     Navigation│
├────────────────────┤
│     Content        │
│   ┌────┐ ┌────┐   │
│   │    │ │    │   │
│   └────┘ └────┘   │
└────────────────────┘
```

### Desktop

```text
┌─────────────────────────────────┐
│ Logo       Navigation           │
├─────────────────────────────────┤
│                                 │
│          Main Content            │
│                                 │
│  ┌────┐  ┌────┐  ┌────┐  ┌────┐│
│  │    │  │    │  │    │  │    ││
│  └────┘  └────┘  └────┘  └────┘│
│                                 │
└─────────────────────────────────┘
```

---

## 🔲 CSS Flexbox

Flexbox is used for one-dimensional layouts.

Example:

```css
.navbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
```

Flexbox is useful for:

* Navigation
* Buttons
* Header layouts
* Card alignment
* Horizontal and vertical alignment

---

## 🧩 CSS Grid

CSS Grid is used for two-dimensional layouts.

Example:

```css
.card-container {
    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 20px;
}
```

Grid is useful for:

* Dashboard cards
* Event cards
* Feature sections
* Multi-column layouts

---

## 📐 Media Queries

Media queries allow the layout to change based on screen size.

Example:

```css
@media (max-width: 768px) {

    .card-container {
        grid-template-columns: 1fr;
    }

}
```

---

## 📱 Mobile-First Approach

The design starts with a mobile-friendly layout and then expands for larger screens.

```text
Mobile
  ↓
Tablet
  ↓
Desktop
```

This improves usability across different devices.

---

## 🎨 UI Design Principles

The following principles are applied:

* Consistent color palette
* Readable typography
* Proper spacing
* Clear visual hierarchy
* Good contrast
* Consistent buttons
* Consistent cards
* Responsive navigation

---

## 🧱 Reusable Components

Common UI components are designed consistently across pages:

* Header
* Navigation
* Buttons
* Cards
* Forms
* Footer
* Sections

This reduces duplicate CSS and improves maintainability.

---

## 🧪 Responsive Testing

The webpages were tested using browser responsive mode.

| Device     | Expected Result              | Status |
| ---------- | ---------------------------- | ------ |
| Mobile     | Single-column layout         | ✅      |
| Tablet     | Adjusted multi-column layout | ✅      |
| Desktop    | Full responsive layout       | ✅      |
| Navigation | Adapts to screen size        | ✅      |
| Cards      | Resize/reflow correctly      | ✅      |
| Typography | Remains readable             | ✅      |
| Images     | Scale correctly              | ✅      |

---

## 📸 Screenshots

Screenshots should demonstrate the responsive design.

Recommended structure:

```text
screenshots/
│
├── home-mobile.png
├── home-tablet.png
├── home-desktop.png
│
├── dashboard-mobile.png
├── dashboard-tablet.png
├── dashboard-desktop.png
│
├── events-mobile.png
├── events-tablet.png
└── events-desktop.png
```

---

## 🚀 Intermediate Extension

Additional pages were added:

* Contact
* Feedback

These pages follow the same responsive design system and navigation structure.

---

## 🔥 Advanced Extension

The advanced extension focuses on reusable layout components using JavaScript libraries such as jQuery.

Possible reusable components include:

```text
Header
Navigation
Card
Modal
Form
Footer
```

The goal is to reduce repetitive code and improve maintainability.

---

## ▶️ How to Run

### Step 1

Open the project in VS Code.

### Step 2

Open:

```text
index.html
```

### Step 3

Run using:

* Browser
* VS Code Live Server

### Step 4

Open Browser DevTools and test:

```text
Mobile
Tablet
Desktop
```

---

## 🎓 Learning Outcomes

After completing this practical, I learned:

* CSS Flexbox
* CSS Grid
* Media Queries
* Responsive design
* Mobile-first design
* Bootstrap components
* Layout organization
* Responsive navigation
* UI consistency
* CSS code organization

---

## 🧪 Evaluation

The project is evaluated based on:

* Responsive behaviour
* Flexbox/Grid usage
* Framework usage
* Visual consistency
* CSS organization
* Mobile-first implementation
* Code readability
* Viva performance

---

## 📚 Practical Details

**Practical:** 03
**Project:** StudentHub Portal
**Topic:** Responsive UI Design using CSS Grid, Flexbox & Bootstrap
**CO Mapping:** CO2
**Technologies:** HTML5, CSS3, Bootstrap
**Tools:** VS Code, Browser Responsive Mode

---

## 👨‍💻 Author

**Tirth**

---

## ⭐ Conclusion

This practical demonstrates how modern CSS layout techniques such as **Flexbox, CSS Grid, Media Queries, and Bootstrap** can be used to create responsive and reusable web interfaces.

The StudentHub pages are designed to provide a consistent user experience across **mobile, tablet, and desktop devices**.
