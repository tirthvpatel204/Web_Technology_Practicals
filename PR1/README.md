# 🚀 Practical 1 – Project Initiation, Requirement Analysis, Sitemap, Wireframe & GitHub Setup

## 📌 Practical Overview

This practical focuses on initiating the semester-long **StudentHub Portal** project by analyzing the project requirements, identifying user roles, defining key modules, planning navigation flow, creating a sitemap and wireframes, and setting up a maintainable GitHub repository.

The objective is to create a strong foundation before starting the actual web development and implementation.

---

## 🎯 Problem Definition

The StudentHub portal is planned as a centralized web platform for students and administrators.

The project foundation includes:

* Requirement analysis
* User role identification
* Module identification
* Navigation planning
* Sitemap creation
* Low-fidelity wireframes
* Project folder structure
* Git/GitHub repository setup
* README documentation

---

## 👥 User Roles

### 👨‍🎓 Student

Students can:

* Register and login
* View dashboard
* Manage profile
* View events
* Submit feedback
* Contact administration
* Access FAQs

### 👨‍💼 Admin

Administrators can:

* Manage students
* Manage events
* View feedback
* Manage portal content
* Monitor student activities

---

## 📄 Planned Pages

The StudentHub portal contains at least 10 pages:

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

## 🗺️ Sitemap

```text
StudentHub
│
├── Home
│
├── About
│
├── Register
│
├── Login
│
├── Dashboard
│   ├── Profile
│   ├── Events
│   └── Feedback
│
├── Contact
│
├── FAQ
│
└── Admin
    ├── Student Management
    ├── Event Management
    └── Feedback Management
```

---

## 🧭 Navigation Flow

```text
Home
 │
 ├── About
 │
 ├── Register
 │       ↓
 │     Login
 │       ↓
 │   Dashboard
 │       ├── Profile
 │       ├── Events
 │       └── Feedback
 │
 ├── FAQ
 │
 └── Contact

Admin Login
     ↓
   Admin
     ├── Students
     ├── Events
     └── Feedback
```

---

## 🖼️ Wireframes

Low-fidelity wireframes were created to visualize the structure and layout of the StudentHub pages before implementation.

Wireframes include:

* Header
* Navigation
* Main content
* Forms
* Cards
* Sidebar where required
* Footer

### Wireframe Screenshots

Add wireframe images to the repository.

Recommended structure:

```text
wireframes/
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

## 📁 Project Structure

```text
StudentHub/
│
├── Practical-1/
│   ├── requirements/
│   ├── sitemap/
│   ├── wireframes/
│   └── README.md
│
├── Practical-2/
│
├── Practical-3/
│
└── README.md
```

---

## 🔗 URL Structure

A URL (Uniform Resource Locator) identifies the location of a resource on the web.

Example:

```text
https://www.example.com/studenthub/login.html
```

Main parts include:

```text
https://
   ↓
Protocol

www.example.com
   ↓
Domain

/studenthub/
   ↓
Path

login.html
   ↓
Resource
```

---

## 🌐 How HTML is Processed

When a user opens an HTML page:

```text
Browser
   ↓
Request HTML
   ↓
Web Server
   ↓
HTML Response
   ↓
HTML Parsing
   ↓
DOM Creation
   ↓
CSS + JavaScript Processing
   ↓
Rendering
   ↓
Webpage Display
```

---

## 🔀 Page Navigation

Navigation between static HTML pages is handled using anchor links.

Example:

```html
<a href="dashboard.html">
    Dashboard
</a>
```

Each page contains a consistent navigation structure so users can move between different sections of the portal.

---

## 🐙 Git & GitHub

Git is used for version control and GitHub is used for remote repository hosting.

After completing each practical, changes are committed and pushed.

Example workflow:

```bash
git add .
git commit -m "Complete Practical 1"
git push origin main
```

---

## 🧪 Deliverables

The following items are submitted:

* Functional requirements
* User roles
* Sitemap
* Wireframes
* Project folder structure
* GitHub repository
* README.md

---

## 🚀 Advanced Extension

### Intermediate

Create responsive wireframes using Figma.

### Advanced

Prepare:

* ER Diagram
* REST-style route plan

Example future routes:

```text
GET    /api/students
GET    /api/students/:id
POST   /api/students
PUT    /api/students/:id
DELETE /api/students/:id

GET    /api/events
POST   /api/events

GET    /api/feedback
POST   /api/feedback
```

---

## 🎓 Learning Outcomes

After completing this practical, the following skills were developed:

* Requirement analysis
* User role identification
* Sitemap planning
* Wireframe design
* Navigation planning
* Project folder organization
* Git version control
* GitHub repository management
* Technical documentation

---

## 🧪 Evaluation

The project is evaluated based on:

* Requirement clarity
* Sitemap completeness
* User role definition
* Wireframe quality
* Repository structure
* GitHub management
* Viva performance

---

## 📚 Practical Details

**Practical:** 01
**Project:** StudentHub Portal
**Topic:** Project Initiation, Requirement Analysis, Sitemap, Wireframe & GitHub Setup
**CO Mapping:** CO1
**Tools:** VS Code, Draw.io/Figma, Git, GitHub

---

## 👨‍💻 Author

**Tirth**
