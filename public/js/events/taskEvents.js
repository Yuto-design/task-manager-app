import { TaskApi } from "/js/api/taskApi.js";
import { renderTasks } from "/js/ui/taskView.js";

function clearTaskForm() {
    document.getElementById("title").value = "";
    document.getElementById("description").value = "";
    document.getElementById("status").value = "todo";
}

export function initTaskEvents() {
    const addBtn = document.getElementById("add-btn");
    const taskList = document.getElementById("task-list");

    addBtn.addEventListener("click", async () => {
        const title = document.getElementById("title").value.trim();
        const description = document.getElementById("description").value.trim();
        const status = document.getElementById("status").value;

        if (!title) {
            alert("タスク名は必須です");
            return;
        }

        await TaskApi.create({ title, description, status });

        const tasks = await TaskApi.getAll();
        renderTasks(tasks);

        clearTaskForm();

        console.log("タスクを追加しました");
        alert("追加しました");
    });

    taskList.addEventListener("click", async (e) => {
        const row = e.target.closest("[data-id]");
        if (!row) return;

        const id = row.dataset.id;

        if (e.target.classList.contains("save-btn")) {
            const title = row.querySelector(".title-input").value.trim();
            const description = row.querySelector(".description-input").value.trim();
            const status = row.querySelector(".status-select").value;

            await TaskApi.update(id, {
                title,
                description,
                status
            });

            console.log(`タスク ${id} を保存しました`);
            alert("保存しました");
        }

        if (e.target.classList.contains("delete-btn")) {
            if (!confirm("削除しますか？")) return;

            await TaskApi.delete(id);
            row.remove();

            console.log(`タスク ${id} を削除しました`);
            alert("削除しました");
        }
    });
}
