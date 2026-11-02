const API_BASE = "api";
const form = document.getElementById("studentForm");
const tableBody = document.getElementById("studentTableBody");
const alertBox = document.getElementById("alertBox");
const searchInput = document.getElementById("searchInput");
const totalStudents = document.getElementById("totalStudents");
const recordCount = document.getElementById("recordCount");
let students = [];
let searchTimer;

function showAlert(message, type = "info") {
    alertBox.textContent = message;
    alertBox.className = `alert ${type}`;
    alertBox.hidden = false;
    window.clearTimeout(showAlert.timer);
    showAlert.timer = window.setTimeout(() => { alertBox.hidden = true; }, 4500);
}
async function apiRequest(endpoint, options = {}) {
    const response = await fetch(`${API_BASE}/${endpoint}`, {
        headers: { "Content-Type": "application/json", ...(options.headers || {}) },
        ...options
    });
    let payload;
    try { payload = await response.json(); }
    catch { throw new Error("The server returned an invalid JSON response."); }
    if (!response.ok || payload.success === false) {
        throw new Error(payload.message || `Request failed (${response.status}).`);
    }
    return payload;
}
function escapeHtml(value) {
    return String(value ?? "").replace(/[&<>"']/g, char => ({
        "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#039;"
    })[char]);
}
function initials(name) {
    return String(name || "?").trim().split(/\s+/).slice(0, 2).map(part => part[0] || "").join("").toUpperCase();
}
function renderStudents(query = "") {
    const normalized = query.trim().toLowerCase();
    const filtered = students.filter(student =>
        [student.name, student.email, student.course].some(value => String(value || "").toLowerCase().includes(normalized))
    );
    totalStudents.textContent = students.length;
    recordCount.textContent = `${filtered.length} record${filtered.length === 1 ? "" : "s"}${normalized ? " found" : ""}`;
    if (!filtered.length) {
        tableBody.innerHTML = `<tr><td colspan="4" class="empty-state">${normalized ? "No students match your search." : "No students yet. Click “Add Student” to create the first record."}</td></tr>`;
        return;
    }
    tableBody.innerHTML = filtered.map(student => `
        <tr>
            <td><div class="student-cell"><span class="student-avatar">${escapeHtml(initials(student.name))}</span><div><div class="student-name">${escapeHtml(student.name)}</div><div class="student-id">ID #${escapeHtml(student.id)}</div></div></div></td>
            <td>${escapeHtml(student.email)}</td>
            <td><span class="course-pill">${escapeHtml(student.course)}</span></td>
            <td><div class="row-actions"><button class="small-btn edit-btn" data-id="${Number(student.id)}">Edit</button><button class="small-btn delete delete-btn" data-id="${Number(student.id)}">Delete</button></div></td>
        </tr>`).join("");
}
async function loadStudents() {
    tableBody.innerHTML = `<tr><td colspan="4" class="empty-state">Loading students...</td></tr>`;
    try {
        const result = await apiRequest("list.php");
        students = Array.isArray(result.data) ? result.data : [];
        renderStudents(searchInput.value);
    } catch (error) {
        tableBody.innerHTML = `<tr><td colspan="4" class="empty-state">Could not load records. ${escapeHtml(error.message)}</td></tr>`;
        totalStudents.textContent = "—";
        recordCount.textContent = "Unable to load records";
        showAlert(error.message, "error");
    }
}
function openForm(student = null) {
    form.hidden = false;
    document.getElementById("formHeading").textContent = student ? "Edit student details" : "Add new student";
    document.getElementById("saveBtn").textContent = student ? "Update Student" : "Save Student";
    document.getElementById("studentId").value = student?.id ?? "";
    document.getElementById("name").value = student?.name ?? "";
    document.getElementById("email").value = student?.email ?? "";
    document.getElementById("course").value = student?.course ?? "";
    form.scrollIntoView({ behavior: "smooth", block: "nearest" });
    document.getElementById("name").focus();
}
function closeForm() {
    form.reset();
    document.getElementById("studentId").value = "";
    form.hidden = true;
}
document.getElementById("showFormBtn").addEventListener("click", () => openForm());
document.getElementById("closeFormBtn").addEventListener("click", closeForm);
document.getElementById("cancelBtn").addEventListener("click", closeForm);
document.getElementById("refreshBtn").addEventListener("click", loadStudents);
searchInput.addEventListener("input", () => {
    window.clearTimeout(searchTimer);
    searchTimer = window.setTimeout(() => renderStudents(searchInput.value), 250);
});
form.addEventListener("submit", async event => {
    event.preventDefault();
    const id = document.getElementById("studentId").value;
    const data = {
        name: document.getElementById("name").value.trim(),
        email: document.getElementById("email").value.trim(),
        course: document.getElementById("course").value.trim()
    };
    if (!data.name || !data.email || !data.course) {
        showAlert("Please fill in all fields.", "error");
        return;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.email)) {
        showAlert("Please enter a valid email address.", "error");
        document.getElementById("email").focus();
        return;
    }
    const saveButton = document.getElementById("saveBtn");
    saveButton.disabled = true;
    saveButton.textContent = id ? "Updating..." : "Saving...";
    try {
        await apiRequest(id ? "update.php" : "add.php", {
            method: "POST",
            body: JSON.stringify(id ? { id: Number(id), ...data } : data)
        });
        closeForm();
        await loadStudents();
        showAlert(id ? "Student updated successfully." : "Student added successfully.", "success");
    } catch (error) {
        showAlert(error.message, "error");
    } finally {
        saveButton.disabled = false;
        saveButton.textContent = id ? "Update Student" : "Save Student";
    }
});
tableBody.addEventListener("click", async event => {
    const editButton = event.target.closest(".edit-btn");
    const deleteButton = event.target.closest(".delete-btn");
    if (editButton) {
        const student = students.find(item => Number(item.id) === Number(editButton.dataset.id));
        if (student) openForm(student);
    }
    if (deleteButton) {
        const id = Number(deleteButton.dataset.id);
        const student = students.find(item => Number(item.id) === id);
        if (!student || !window.confirm(`Delete ${student.name}'s record? This cannot be undone.`)) return;
        deleteButton.disabled = true;
        try {
            await apiRequest("delete.php", { method: "POST", body: JSON.stringify({ id }) });
            await loadStudents();
            showAlert("Student deleted successfully.", "success");
        } catch (error) {
            showAlert(error.message, "error");
            deleteButton.disabled = false;
        }
    }
});
loadStudents();
