export function searchEvents(events, keyword) {
    return events.filter(event =>
        event.title.toLowerCase().includes(keyword.toLowerCase())
    );
}

export function filterEvents(events, category) {
    if (category === "all") {
        return events;
    }

    return events.filter(event => event.category === category);
}

export function sortEvents(events, sortType) {
    const result = [...events];

    if (sortType === "titleAsc") {
        return result.sort((a, b) => a.title.localeCompare(b.title));
    }

    if (sortType === "titleDesc") {
        return result.sort((a, b) => b.title.localeCompare(a.title));
    }

    if (sortType === "dateAsc") {
        return result.sort((a, b) => new Date(a.date) - new Date(b.date));
    }

    if (sortType === "dateDesc") {
        return result.sort((a, b) => new Date(b.date) - new Date(a.date));
    }

    return result;
}

export function paginate(items, page, itemsPerPage) {
    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;

    return items.slice(start, end);
}