import { TaskApi } from "../api/taskApi.js";
import { renderTasks } from "../ui/taskView.js";

export function initTaskEvents() {

    document.getElementById("add-btn").addEventListener("click", async () => {
        const title = document.getElementById("title").value;
        const description = document.getElementById("description").value;
        const status = document.getElementById("status").value;

        if (!title) return;

        await TaskApi.create({ title, description, status });

        renderTasks(await TaskApi.getAll());
    });

    document.getElementById("task-list").addEventListener("change", async (e) => {
        if (!e.target.classList.contains("status-select")) return;

        const li = e.target.closest("li");
        const id = li.dataset.id;

        await TaskApi.update(id, { status: e.target.value });
    });

    document.getElementById("task-list").addEventListener("click", async (e) => {
        if (!e.target.classList.contains("delete-btn")) return;

        const li = e.target.closest("li");
        const id = li.dataset.id;

        await TaskApi.delete(id);
        li.remove();
    });
}
