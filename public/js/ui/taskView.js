export function renderTasks(tasks) {
    const list = document.getElementById("task-list");
    list.innerHTML = "";

    tasks.forEach(task => {
        const tr = document.createElement("tr");
        tr.dataset.id = task.id;

        tr.classList.add(`status-${task.status}`);

        tr.innerHTML = `
            <td>
                <input class="title-input" value="${task.title}">
            </td>
            <td>
                <input class="description-input" value="${task.description ?? ""}">
            </td>
            <td>
                <select class="status-select">
                    <option value="todo" ${task.status === "todo" ? "selected" : ""}>todo</option>
                    <option value="doing" ${task.status === "doing" ? "selected" : ""}>doing</option>
                    <option value="done" ${task.status === "done" ? "selected" : ""}>done</option>
                </select>
            </td>
            <td>
                <button class="save-btn">保存</button>
                <button class="delete-btn">削除</button>
            </td>
        `;

        list.appendChild(tr);
    });
}
