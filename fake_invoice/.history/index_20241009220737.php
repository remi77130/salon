<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Créer une facture</h1>

        <!-- Company and Client Information Section -->
        <div class="form-section">
            <!-- Company Info -->
            <div class="company-info">
                <h3>Informations de l'entreprise</h3>
                <label for="logo">Logo:</label>
                <input type="file" id="logo"><br><br>
                <label for="company-name">Nom de l'entreprise:</label>
                <input type="text" id="company-name"><br><br>
                <label for="address">Adresse:</label>
                <input type="text" id="address"><br><br>
                <label for="postal-code">Code postal:</label>
                <input type="text" id="postal-code">
                <label for="city">Ville:</label>
                <input type="text" id="city"><br><br>
                <label for="phone">Téléphone:</label>
                <input type="text" id="phone"><br><br>
                <label for="email">Email:</label>
                <input type="email" id="email"><br><br>
                <label for="siret">SIREN/SIRET:</label>
                <input type="text" id="siret"><br><br>
            </div>

            <!-- Client Info -->
            <div class="client-info">
                <h3>Informations du client</h3>
                <label for="client-code">Code client (Facultatif):</label>
                <input type="text" id="client-code"><br><br>
                <label for="client-name">Nom et prénom du client:</label>
                <input type="text" id="client-name"><br><br>
                <label for="client-address">Adresse:</label>
                <input type="text" id="client-address"><br><br>
                <label for="client-postal">Code postal:</label>
                <input type="text" id="client-postal">
                <label for="client-city">Ville:</label>
                <input type="text" id="client-city"><br><br>
                <label for="client-phone">Téléphone:</label>
                <input type="text" id="client-phone"><br><br>
            </div>
        </div>

        <!-- Invoice Information -->
        <div class="invoice-info">
            <h3>Informations de la facture</h3>
            <label for="invoice-number">FACTURE N°:</label>
            <input type="text" id="invoice-number"><br><br>
            <label for="invoice-date">Date d'émission:</label>
            <input type="date" id="invoice-date" value="2024-10-09"><br><br>
            <label for="execution-date">Date d'exécution de la vente:</label>
            <input type="date" id="execution-date" value="2024-10-09"><br><br>
        </div>

        <!-- Products/Services Table -->
        <h3>Produits/Services</h3>
        <table id="invoice-table">
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Désignation</th>
                    <th>Quantité</th>
                    <th>Unité</th>
                    <th>Prix unitaire HT</th>
                    <th>Remise %</th>
                    <th>Montant HT</th>
                    <th>TVA %</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="text" placeholder="Référence"></td>
                    <td><input type="text" placeholder="Nom du produit"></td>
                    <td><input type="number" value="0"></td>
                    <td>
                        <select>
                            <option value="unité">Unité</option>
                            <option value="kg">Kg</option>
                            <option value="pcs">Pcs</option>
                        </select>
                    </td>
                    <td><input type="number" value="0.00"></td>
                    <td><input type="number" value="0.00"></td>
                    <td><input type="number" value="0.00" disabled></td>
                    <td>
                        <select>
                            <option value="0">0%</option>
                            <option value="10">10%</option>
                            <option value="20">20%</option>
                        </select>
                    </td>
                    <td>
                        <button class="add-row">+</button>
                        <button class="remove-row">-</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Add Row Button -->
        <p><button id="add-line">Ajouter une ligne</button></p>

        <!-- Totals Section -->
        <div class="totals">
            <p>Total HT: <span id="total-ht">0.00</span></p>
            <p>Total TTC: <span id="total-ttc">0.00</span></p>
            <p>Net à payer (€): <span id="net-to-pay">0.00</span></p>
        </div>

        <!-- PDF Generation Button -->
        <button id="generate-pdf" class="pdf-button">Créer le PDF</button>
    </div>

    <script src="script.js"></script>

    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
</body>
</html>
