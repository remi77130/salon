document.addEventListener("DOMContentLoaded", function() {
    const { jsPDF } = window.jspdf;

    // Fonctionnalité pour ajouter une ligne
    document.querySelector('#add-line').addEventListener('click', function() {
        let table = document.querySelector('#invoice-table tbody');
        let newRow = table.rows[0].cloneNode(true); // Cloner la première ligne
        table.appendChild(newRow);
    });

    // Fonctionnalité pour supprimer une ligne
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-row')) {
            let row = event.target.closest('tr');
            let table = document.querySelector('#invoice-table tbody');
            if (table.rows.length > 1) {
                row.remove();
            }
        }
    });

    // Fonction pour générer le PDF
    document.querySelector('#generate-pdf').addEventListener('click', function() {
        // Initialiser jsPDF
        const doc = new jsPDF();

        // Récupérer les informations de l'entreprise
        const companyName = document.getElementById('company-name').value;
        const address = document.getElementById('address').value;
        const postalCode = document.getElementById('postal-code').value;
        const city = document.getElementById('city').value;
        const phone = document.getElementById('phone').value;
        const email = document.getElementById('email').value;

        // Récupérer les informations du client
        const clientName = document.getElementById('client-name').value;
        const clientAddress = document.getElementById('client-address').value;
        const clientPostal = document.getElementById('client-postal').value;
        const clientCity = document.getElementById('client-city').value;

        // Ajout du titre et des informations de l'entreprise
        doc.setFontSize(12);
        doc.text(`Facture de: ${companyName}`, 10, 10);
        doc.text(`Adresse: ${address}, ${postalCode} ${city}`, 10, 20);
        doc.text(`Téléphone: ${phone}`, 10, 30);
        doc.text(`Email: ${email}`, 10, 40);

        // Informations du client
        doc.text(`Facturé à: ${clientName}`, 10, 50);
        doc.text(`Adresse: ${clientAddress}, ${clientPostal} ${clientCity}`, 10, 60);

        // Ajout des lignes de produits/services
        let startY = 70; // Position Y pour commencer à écrire
        let table = document.querySelector('#invoice-table tbody');
        let rows = table.querySelectorAll('tr');
        doc.text('Référence | Désignation | Quantité | Unité | Prix unitaire HT | Remise % | TVA %', 10, startY);
        startY += 10;

        rows.forEach(row => {
            let ref = row.cells[0].querySelector('input').value;
            let designation = row.cells[1].querySelector('input').value;
            let quantity = row.cells[2].querySelector('input').value;
            let unit = row.cells[3].querySelector('select').value;
            let price = row.cells[4].querySelector('input').value;
            let discount = row.cells[5].querySelector('input').value;
            let vat = row.cells[7].querySelector('select').value;

            let rowText = `${ref} | ${designation} | ${quantity} | ${unit} | ${price} | ${discount} | ${vat}`;
            doc.text(rowText, 10, startY);
            startY += 10;
        });

        // Total
        let totalHT = document.getElementById('total-ht').textContent;
        let totalTTC = document.getElementById('total-ttc').textContent;
        let netToPay = document.getElementById('net-to-pay').textContent;

        doc.text(`Total HT: ${totalHT} €`, 10, startY + 10);
        doc.text(`Total TTC: ${totalTTC} €`, 10, startY + 20);
        doc.text(`Net à payer: ${netToPay} €`, 10, startY + 30);

        // Enregistrer le PDF
        doc.save('facture.pdf');
    });
});
