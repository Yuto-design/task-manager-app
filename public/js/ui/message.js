export function showMessage(text, type = "success") {
    const el = document.getElementById("message");

    el.textContent = text;
    el.className = `message ${type}`;
    el.style.display = "block";

    setTimeout(() => {
        el.style.display = "none";
    }, 3000);
}
