import { getTasks, createTask } from "../api/taskApi.js";
import { renderTasks } from "../ui/taskView.js";

export async function initTaskEvents() {
    document.getElementById("add-btn").addEventListener("click", async () => {
        const title = document.getElementById("title").value;
        if(!title) return;

        await createTask(title);
        document.getElementById("title").value = "";

        const tasks = await getTasks();
        renderTasks(tasks);
    });
}