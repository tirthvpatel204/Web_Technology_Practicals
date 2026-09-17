export async function fetchEvents() {
    const response = await fetch("data/events.json");

    if (!response.ok) {
        throw new Error("Failed to load events");
    }

    return await response.json();
}