document.addEventListener("DOMContentLoaded", function() {
    const { jsPDF } = window.jspdf;
 
    // Image d'arrière-plan encodée en base64 (mettez ici votre propre base64)
    const backgroundBase64 = 'data:image/png;base64,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'

    // Dimensions de l'image d'arrière-plan (par exemple, 150mm x 150mm)
    const bgWidth = 150;  // Largeur de l'image d'arrière-plan
    const bgHeight = 150;  // Hauteur de l'image d'arrière-plan
     // Calculer les positions pour centrer l'image
     const bgX = (pageWidth - bgWidth) / 2;  // Centrer horizontalement
     const bgY = (pageHeight - bgHeight) / 2;  // Centrer verticalement

     // Ajouter l'image d'arrière-plan (centrée)
     doc.addImage(backgroundBase64, 'PNG', bgX, bgY, bgWidth, bgHeight);

       // Gestion du téléchargement du logo
       document.getElementById('logo').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                logoBase64 = e.target.result; // Stocker l'image du logo en base64
            };
            reader.readAsDataURL(file);  // Lire le fichier comme base64
        }
    });


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
