export function renderTasks(tasks) {
    const list = document.getElementById("task-list");
    list.innerHTML = "";

    tasks.forEach(task => {
        const tr = document.createElement("tr");
        tr.dataset.id = task.id;

        tr.classList.add(`task-${task.status}`);

        tr.innerHTML = `
            <td><input class="title-input" value="${task.title}"></td>
            <td><input class="description-input" value="${task.description ?? ""}"></td>
            <td>
                <select class="status-select">
                    <option class="status-todo" value="todo" ${task.status === "todo" ? "selected" : ""}>todo</option>
                    <option class="status-doing" value="doing" ${task.status === "doing" ? "selected" : ""}>doing</option>
                    <option class="status-done" value="done" ${task.status === "done" ? "selected" : ""}>done</option>
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

const statusSelect = document.getElementById("status");

function updateStatusColor() {
    const value = statusSelect.value;
    statusSelect.className = '';

    if (value === 'todo') {
        statusSelect.style.color = '#fc8181';
        statusSelect.style.fontWeight = '600';
    } else if (value === 'doing') {
        statusSelect.style.color = '#f6ad55';
        statusSelect.style.fontWeight = '600';
    } else if (value === 'done') {
        statusSelect.style.color = '#68d391';
        statusSelect.style.fontWeight = '600';
    }
}

updateStatusColor();

statusSelect.addEventListener('change', updateStatusColor);
