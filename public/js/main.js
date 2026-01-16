import { TaskApi } from "./api/taskApi.js";
import { renderTasks } from "./ui/taskView.js";
import { initTaskEvents } from "./events/taskEvents.js";

async function init() {
    const tasks = await TaskApi.getAll();
    renderTasks(tasks);
    initTaskEvents();
}

init();
