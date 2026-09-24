export function renderCards(data, type, container) {
  if (!data.length) {
    container.innerHTML = `<div class="card"><h3>No records found</h3><p>Try changing your search or filter.</p></div>`;
    return;
  }

  container.innerHTML = data.map(item => {
    if (type === "events") {
      return `<article class="card">
        <span class="number">EVENT #${item.id}</span>
        <h3>${item.title}</h3>
        <p>📅 ${item.date}</p>
        <p>📍 ${item.venue}</p>
        <span class="tag">${item.category}</span>
      </article>`;
    }

    if (type === "students") {
      return `<article class="card">
        <span class="number">STUDENT #${item.id}</span>
        <h3>${item.name}</h3>
        <p>🎓 ${item.course} • Year ${item.year}</p>
        <p>📍 ${item.city}</p>
        <p>✉️ ${item.email}</p>
      </article>`;
    }

    return `<article class="card">
      <span class="number">FAQ #${item.id}</span>
      <h3>${item.question}</h3>
      <p>${item.answer}</p>
      <span class="tag">${item.category}</span>
    </article>`;
  }).join("");
}
