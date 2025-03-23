// Show notification pop-up
function showNotification(message) {
    const notification = document.getElementById("notification");
    notification.innerHTML = message;
    notification.style.display = "block";

    setTimeout(() => {
        notification.style.display = "none";
    }, 7000);
}

// Fetch and display notifications
function checkNotifications() {
    fetch("notifications.php")
        .then(response => response.json())
        .then(data => {
            let message = "";

            if (data.lowStock.length > 0) {
                message += `<b>Low Stock:</b> ${data.lowStock.join(", ")}<br>`;
            }
            
            if (data.nearExpiry.length > 0) {
                message += "<b>Nearing Expiry:</b><br>";
                data.nearExpiry.forEach(medicine => {
                    message += `⚠ ${medicine.name} (Expiry: ${medicine.expiry_date})<br>`;
                });
            }

            if (message !== "") {
                showNotification(message);
            }
        })
        .catch(error => console.error("Error fetching notifications:", error));
}

// Run notifications when page loads
window.onload = function () {
    checkNotifications();
};

// search
function searchTable(inputId, tableId) {
    let input = document.getElementById(inputId).value.toLowerCase();
    let table = document.getElementById(tableId);
    let rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) {
        let cells = rows[i].getElementsByTagName("td");
        let match = false;
        
        for (let cell of cells) {
            if (cell.innerText.toLowerCase().includes(input)) {
                match = true;
                break;
            }
        }

        rows[i].style.display = match ? "" : "none";
    }
}
