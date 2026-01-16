export function renderTasks(tasks) {
    const list = document.getElementById("task-list");
    list.innerHTML = "";

    tasks.forEach(task => {
        const li = document.createElement("li");
        li.textContent = `${task.id}: ${task.title} [${task.status}]`;
        list.appendChild(li);
    });
}