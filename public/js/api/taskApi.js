const BASE_URL = "http://localhost:8080/api/tasks";

export async function getTasks() {
    const res = await fetch(BASE_URL);
    return res.json();
}

export async function createTask(title) {
    await fetch(BASE_URL, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            title,
            status: "todo"
        })
    });
}