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

// Function to adjust quantity on the dashboard only (no effect on manage equipments)
function adjustQuantity(button, change) {
    // Prevent the default behavior, ensuring no page scroll or form submission
    button.addEventListener('click', function(e) {
        e.preventDefault();  // Prevent any default action, including scrolling
        
        // Get the closest td element and locate the span with the adjustable quantity
        const quantitySpan = button.closest("td").querySelector(".adjustable-quantity");

        // Parse the current quantity as an integer
        let quantity = parseInt(quantitySpan.textContent);
        
        // Apply the change: increment or decrement
        quantity += change;

        // Prevent negative values
        if (quantity < 0) quantity = 0;

        // Update the quantity displayed on the dashboard
        quantitySpan.textContent = quantity;
    });
}

// Function to ensure quantity adjustment is only applied on the dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Grab all the buttons for adjusting quantity
    const buttons = document.querySelectorAll('.qty-btn');
    
    buttons.forEach(button => {
        // Add event listener to each button
        button.addEventListener('click', function(event) {
            // Determine whether the button is + or -
            const change = button.textContent === '+' ? 1 : -1;
            
            // Call adjustQuantity, passing the correct amount to increment or decrement
            adjustQuantity(button, change);
        });
    });
});
