import { TaskApi } from "/js/api/taskApi.js";
import { renderTasks } from "/js/ui/taskView.js";
import { initTaskEvents } from "/js/events/taskEvents.js";

async function init() {
    const tasks = await TaskApi.getAll();
    renderTasks(tasks);
    initTaskEvents();
}

init();
