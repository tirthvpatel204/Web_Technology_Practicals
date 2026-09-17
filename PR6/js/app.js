import { fetchEvents } from "./api.js";

import {
    searchEvents,
    filterEvents,
    sortEvents,
    paginate
} from "./utils.js";

import {
    renderEvents,
    renderCategories,
    renderPagination
} from "./render.js";

let allEvents = [];
let currentPage = 1;

const itemsPerPage = 6;

const searchInput = document.getElementById("searchInput");
const categoryFilter = document.getElementById("categoryFilter");
const sortSelect = document.getElementById("sortSelect");

async function loadEvents() {
    try {
        const events = await fetchEvents();

        allEvents = events;

        renderCategories(allEvents);

        updateUI();

        document.getElementById("loading").style.display = "none";

    } catch (error) {

        document.getElementById("loading").style.display = "none";

        document.getElementById("error").textContent =
            "Error loading events: " + error.message;
    }
}

function updateUI() {

    let result = [...allEvents];

    const keyword = searchInput.value;

    const category = categoryFilter.value;

    const sortType = sortSelect.value;

    result = searchEvents(result, keyword);

    result = filterEvents(result, category);

    result = sortEvents(result, sortType);

    const paginatedEvents = paginate(
        result,
        currentPage,
        itemsPerPage
    );

    renderEvents(paginatedEvents);

    renderPagination(
        result.length,
        currentPage,
        itemsPerPage,
        page => {
            currentPage = page;
            updateUI();
        }
    );
}

searchInput.addEventListener("input", () => {
    currentPage = 1;
    updateUI();
});

categoryFilter.addEventListener("change", () => {
    currentPage = 1;
    updateUI();
});

sortSelect.addEventListener("change", () => {
    currentPage = 1;
    updateUI();
});

loadEvents();