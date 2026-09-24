export function renderPagination(total, page, pageSize, container, onPageChange) {
  const totalPages = Math.ceil(total / pageSize);
  container.innerHTML = "";
  if (totalPages <= 1) return;

  const prev = document.createElement("button");
  prev.className = "page-btn";
  prev.textContent = "←";
  prev.disabled = page === 1;
  prev.onclick = () => onPageChange(page - 1);
  container.appendChild(prev);

  for (let i = 1; i <= totalPages; i++) {
    const btn = document.createElement("button");
    btn.className = `page-btn ${i === page ? "active" : ""}`;
    btn.textContent = i;
    btn.onclick = () => onPageChange(i);
    container.appendChild(btn);
  }

  const next = document.createElement("button");
  next.className = "page-btn";
  next.textContent = "→";
  next.disabled = page === totalPages;
  next.onclick = () => onPageChange(page + 1);
  container.appendChild(next);
}
