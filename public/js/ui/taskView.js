export function renderTasks(tasks) {
    const list = document.getElementById("task-list");
    list.innerHTML = "";

    tasks.forEach(task => {
        const statusClass = `status-${task.status}`;

        const li = document.createElement("li");
        li.dataset.id = task.id;

        li.innerHTML = `
            <input class="edit-title" value="${task.title}">

            <input class="edit-desc"
                value="${task.description ?? ""}"
                placeholder="詳細">

            <select class="status-select ${statusClass}">
                <option value="todo" ${task.status === "todo" ? "selected" : ""}>todo</option>
                <option value="doing" ${task.status === "doing" ? "selected" : ""}>doing</option>
                <option value="done" ${task.status === "done" ? "selected" : ""}>done</option>
            </select>

            <button class="save-btn">保存</button>
            <button class="delete-btn">削除</button>
        `;

        list.appendChild(li);
    });
}
