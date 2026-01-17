export function renderTasks(tasks) {
    const list = document.getElementById("task-list");
    list.innerHTML = "";

    tasks.forEach(task => {
        const li = document.createElement("li");
        li.dataset.id = task.id;

        li.innerHTML = `
            <span class="task-title">${task.title}</span>

            <select class="status-select">
                <option value="todo" ${task.status === "todo" ? "selected" : ""}>todo</option>
                <option value="doing" ${task.status === "doing" ? "selected" : ""}>doing</option>
                <option value="done" ${task.status === "done" ? "selected" : ""}>done</option>
            </select>

            <button class="delete-btn">削除</button>
        `;

        list.appendChild(li);
    });
}
