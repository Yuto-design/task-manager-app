const BASE_URL = "/api/tasks";

export class TaskApi {
    static async getAll() {
        const res = await fetch(BASE_URL);

        if (!res.ok) {
            throw new Error('Failed to fetch tasks');
        }

        return res.json();
    }

    static async get(id) {
        const res = await fetch(`${BASE_URL}/${id}`);

        if (!res.ok) {
            throw new Error(`Failed to fetch task: ${id}`);
        }

        return res.json();
    }

    static async create(data) {
        const res = await fetch(BASE_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        if (!res.ok) {
            throw new Error('Failed to create task');
        }

        return res.json();
    }

    static async update(id, data) {
        const res = await fetch(`${BASE_URL}/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        if (!res.ok) {
            throw new Error(`Failed to update task: ${id}`);
        }

        return res.json();
    }

    static async delete(id) {
        const res = await fetch(`${BASE_URL}/${id}`, {
            method: 'DELETE',
        });

        if (!res.ok) {
            throw new Error(`Failed to delete task: ${id}`);
        }
    }
}