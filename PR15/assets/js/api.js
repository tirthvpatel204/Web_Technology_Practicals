// Fetch API Wrapper
async function fetchStudentsAPI() {
    try {
        let response = await fetch('<?= BASE_URL ?>api/students.php');
        let data = await response.json();
        return data;
    } catch(err) {
        console.error('API Error:', err);
    }
}
