import { getTasks } from "./api/taskApi.js";
import { renderTasks } from "./ui/taskView.js";
import { initTaskEvents } from "./events/taskEvents.js";

async function init() {
    const tasks = await getTasks();
    renderTasks(tasks);
    initTaskEvents();
}

init();