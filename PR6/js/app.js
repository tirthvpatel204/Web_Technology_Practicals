import { fetchJSON } from "./fetchData.js";
import { renderCards } from "./render.js";
import { renderPagination } from "./pagination.js";

const paths = {
  events: "../data/events.json",
  students: "../data/students.json",
  faqs: "../data/faqs.json"
};

const state = {
  type: "events",
  data: [],
  filtered: [],
  page: 1,
  pageSize: 6,
  search: "",
  filter: "all",
  sort: "default"
};

const container = document.getElementById("dataContainer");
const status = document.getElementById("status");
const pagination = document.getElementById("pagination");
const searchInput = document.getElementById("searchInput");
const filterSelect = document.getElementById("filterSelect");
const sortSelect = document.getElementById("sortSelect");
const pageSize = document.getElementById("pageSize");
const recordCount = document.getElementById("recordCount");

async function loadData(type) {
  status.className = "status";
  status.textContent = "Loading JSON data...";
  container.innerHTML = "";
  pagination.innerHTML = "";

  try {
    const cacheKey = `practical6_${type}`;
    let data;

    try {
      const response = await fetch(paths[type]);
      if (!response.ok) throw new Error("Fetch failed");
      data = await response.json();
      localStorage.setItem(cacheKey, JSON.stringify(data));
    } catch (networkError) {
      const cached = localStorage.getItem(cacheKey);
      if (!cached) throw networkError;
      data = JSON.parse(cached);
      status.textContent = "Offline mode: showing cached data.";
    }

    state.data = data;
    state.page = 1;
    buildFilter();
    applyFilters();
  } catch (error) {
    status.className = "status error";
    status.textContent = "Error: Could not load JSON data.";
    container.innerHTML = `<div class="card"><h3>Data unavailable</h3><p>Run this project through a local server such as VS Code Live Server.</p></div>`;
  }
}

function buildFilter() {
  const key = state.type === "students" ? "course" : "category";
  const values = [...new Set(state.data.map(item => item[key]))].sort();
  filterSelect.innerHTML = `<option value="all">All ${state.type === "students" ? "Courses" : "Categories"}</option>`;
  values.forEach(value => {
    filterSelect.innerHTML += `<option value="${value}">${value}</option>`;
  });
  state.filter = "all";
}

function applyFilters() {
  const query = state.search.toLowerCase();
  const key = state.type === "students" ? "course" : "category";

  state.filtered = state.data.filter(item => {
    const searchable = Object.values(item).join(" ").toLowerCase();
    const matchesSearch = searchable.includes(query);
    const matchesFilter = state.filter === "all" || item[key] === state.filter;
    return matchesSearch && matchesFilter;
  });

  const titleKey = state.type === "events" ? "title" :
                   state.type === "students" ? "name" : "question";

  if (state.sort === "az") {
    state.filtered.sort((a,b) => a[titleKey].localeCompare(b[titleKey]));
  } else if (state.sort === "za") {
    state.filtered.sort((a,b) => b[titleKey].localeCompare(a[titleKey]));
  }

  const start = (state.page - 1) * state.pageSize;
  const pageData = state.filtered.slice(start, start + state.pageSize);

  renderCards(pageData, state.type, container);
  renderPagination(state.filtered.length, state.page, state.pageSize, pagination, page => {
    state.page = page;
    applyFilters();
    window.scrollTo({top: 280, behavior: "smooth"});
  });

  recordCount.textContent = state.filtered.length;
  if (!status.textContent.startsWith("Offline")) {
    status.textContent = `${state.filtered.length} matching record(s)`;
  }
}

document.querySelectorAll(".tab").forEach(tab => {
  tab.addEventListener("click", () => {
    document.querySelector(".tab.active").classList.remove("active");
    tab.classList.add("active");
    state.type = tab.dataset.type;
    state.search = "";
    state.sort = "default";
    searchInput.value = "";
    sortSelect.value = "default";
    loadData(state.type);
  });
});

searchInput.addEventListener("input", e => {
  state.search = e.target.value;
  state.page = 1;
  applyFilters();
});

filterSelect.addEventListener("change", e => {
  state.filter = e.target.value;
  state.page = 1;
  applyFilters();
});

sortSelect.addEventListener("change", e => {
  state.sort = e.target.value;
  state.page = 1;
  applyFilters();
});

pageSize.addEventListener("change", e => {
  state.pageSize = Number(e.target.value);
  state.page = 1;
  applyFilters();
});

loadData("events");
