# Practical 6 - Rendering External JSON Data using Fetch API

## Features
- Fetch external JSON files using Fetch API
- Dynamic rendering
- Search
- Category/course filtering
- A-Z / Z-A sorting
- Pagination
- Loading and error handling
- Modular JavaScript using ES6 modules
- localStorage caching for offline-like display
- Events, Students and FAQs datasets
- Responsive UI

## Run
Use VS Code Live Server or another local HTTP server. Do not open `index.html` directly with `file://` because browser fetch restrictions may block JSON requests.

## Files
- `data/events.json` - 15 event records
- `data/students.json` - 15 student records
- `data/faqs.json` - 15 FAQ records
- `js/fetchData.js` - Fetch API logic
- `js/render.js` - Dynamic card rendering
- `js/pagination.js` - Pagination logic
- `js/app.js` - Application state, search, filter and sorting
