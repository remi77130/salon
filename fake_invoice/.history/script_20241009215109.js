document.addEventListener("DOMContentLoaded", function() {
    
    // Add Row Functionality
    document.querySelector('#add-line').addEventListener('click', function() {
        let table = document.querySelector('#invoice-table tbody');
        let newRow = table.rows[0].cloneNode(true); // Clone the first row
        table.appendChild(newRow);
    });

    // Remove Row Functionality
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-row')) {
            let row = event.target.closest('tr');
            let table = document.querySelector('#invoice-table tbody');
            if (table.rows.length > 1) {
                row.remove();
            }
        }
    });

    // PDF Generation (Placeholder for now)
    document.querySelector('#generate-pdf').addEventListener('click', function() {
        alert("PDF generation will be implemented here!");
    });

    // Calculations can be added here for totals...
});
