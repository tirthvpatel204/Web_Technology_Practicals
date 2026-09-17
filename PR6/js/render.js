export function renderEvents(events) {
    const container = document.getElementById("eventContainer");

    if (events.length === 0) {
        container.innerHTML = "<p>No events found.</p>";
        return;
    }

    container.innerHTML = events.map(event => `
        <div class="card">
            <h2>${event.title}</h2>
            <p><strong>Category:</strong> ${event.category}</p>
            <p><strong>Date:</strong> ${event.date}</p>
            <p><strong>Location:</strong> ${event.location}</p>
            <p><strong>Organizer:</strong> ${event.organizer}</p>
        </div>
    `).join("");
}

export function renderCategories(events) {
    const select = document.getElementById("categoryFilter");

    const categories = [...new Set(events.map(event => event.category))];

    categories.forEach(category => {
        const option = document.createElement("option");

        option.value = category;
        option.textContent = category;

        select.appendChild(option);
    });
}

export function renderPagination(totalItems, currentPage, itemsPerPage, onPageChange) {
    const pagination = document.getElementById("pagination");

    const totalPages = Math.ceil(totalItems / itemsPerPage);

    pagination.innerHTML = "";

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement("button");

        button.textContent = i;
        button.className = "page-btn";

        if (i === currentPage) {
            button.classList.add("active");
        }

        button.addEventListener("click", () => {
            onPageChange(i);
        });

        pagination.appendChild(button);
    }
}