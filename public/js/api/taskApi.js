const BASE_URL = "/tasks";

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute("content");

const defaultOptions = {
    headers: { "Accept": "application/json" },
    credentials: "same-origin",
};

const jsonHeaders = {
    ...defaultOptions.headers,
    "Content-Type": "application/json",
    "X-CSRF-TOKEN": csrfToken,
};

export class TaskApi {

    static async getAll() {
        const res = await fetch("/tasks/list", defaultOptions);
        return res.json();
    }

    static async get(id) {
        const res = await fetch(`${BASE_URL}/${id}`, defaultOptions);

        if (!res.ok) {
            throw new Error(`Failed to fetch task: ${id}`);
        }

        return res.json();
    }

    static async create(data) {
        const res = await fetch(BASE_URL, {
            ...defaultOptions,
            method: "POST",
            headers: jsonHeaders,
            body: JSON.stringify(data),
        });

        if (!res.ok) {
            throw new Error("Failed to create task");
        }

        return res.json();
    }

    static async update(id, data) {
        const res = await fetch(`${BASE_URL}/${id}`, {
            ...defaultOptions,
            method: "PUT",
            headers: jsonHeaders,
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
            ...defaultOptions,
            method: "DELETE",
            headers: { ...defaultOptions.headers, "X-CSRF-TOKEN": csrfToken },
        });

        if (!res.ok) {
            const error = await res.text();
            throw new Error(`Failed to delete task ${id}: ${error}`);
        }
    }
}
