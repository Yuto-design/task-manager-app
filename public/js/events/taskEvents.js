import { TaskApi } from "../api/taskApi.js";
import { renderTasks } from "../ui/taskView.js";

export function initTaskEvents() {
    document.getElementById("add-btn").addEventListener("click", async () => {
        const title = document.getElementById("title").value;
        const description = document.getElementById("description").value;
        const status = document.getElementById("status").value;

        if (!title) {
            alert("タイトルは必須です");
            return;
        }

        await TaskApi.create({ title, description, status });

        const tasks = await TaskApi.getAll();
        renderTasks(tasks);

        document.getElementById("title").value = "";
        document.getElementById("description").value = "";
        document.getElementById("status").value = "todo";
    });

    document.getElementById("task-list").addEventListener("click", async (e) => {
        const li = e.target.closest("li");
        if (!li) return;

        const id = li.dataset.id;

        if (e.target.classList.contains("save-btn")) {
            const title = li.querySelector(".edit-title").value;
            const description = li.querySelector(".edit-desc").value;
            const status = li.querySelector(".status-select").value;

            await TaskApi.update(id, { title, description, status });
            alert("保存しました");
        }

        if (e.target.classList.contains("delete-btn")) {
            await TaskApi.delete(id);
            li.remove();
        }
    });

    document.getElementById("task-list").addEventListener("change", (e) => {
        if (!e.target.classList.contains("status-select")) return;

        e.target.classList.remove("status-todo", "status-doing", "status-done");
        e.target.classList.add(`status-${e.target.value}`);
    });
}
