document.addEventListener("DOMContentLoaded", function() {

    // Add event listener for adding a new row
    document.querySelector('.add-row').addEventListener('click', function() {
        let table = document.querySelector('#invoice-table tbody');
        let newRow = table.rows[0].cloneNode(true); // Clone the first row
        table.appendChild(newRow);
    });

    // Add event listener for removing a row
    document.querySelector('.remove-row').addEventListener('click', function(e) {
        let row = e.target.closest('tr');
        let table = document.querySelector('#invoice-table tbody');
        if (table.rows.length > 1) {
            row.remove();
        }
    });

    // Here you would add event listeners for input changes to update totals...
});
