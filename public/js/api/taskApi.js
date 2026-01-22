const BASE_URL = "/tasks";

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute("content");

export class TaskApi {

    static async getAll() {
        const res = await fetch("/tasks/list", {
            headers: { "Accept": "application/json" },
            credentials: "same-origin",
        });
        return res.json();
    }

    static async get(id) {
        const res = await fetch(`${BASE_URL}/${id}`, {
            headers: {
                "Accept": "application/json",
            },
            credentials: "same-origin",
        });

        if (!res.ok) {
            throw new Error(`Failed to fetch task: ${id}`);
        }

        return res.json();
    }

    static async create(data) {
        const res = await fetch(BASE_URL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                "Accept": "application/json",
            },
            credentials: "same-origin",
            body: JSON.stringify(data),
        });

        if (!res.ok) {
            throw new Error("Failed to create task");
        }

        return res.json();
    }

    static async update(id, data) {
        const res = await fetch(`${BASE_URL}/${id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json",
            },
            credentials: "same-origin",
            body: JSON.stringify(data),
        });

        if (!res.ok) {
            const error = await res.text();
            throw new Error(`Failed to update task ${id}: ${error}`);
        }

        return res.json();
    }

    static async delete(id) {
        const res = await fetch(`${BASE_URL}/${id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json",
            },
            credentials: "same-origin",
        });

        if (!res.ok) {
            const error = await res.text();
            throw new Error(`Failed to delete task ${id}: ${error}`);
        }
    }
}
