<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice fake</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h1>Invoice Form</h1>

        <div class="form-section">
            <div class="company-info">
                <h3>Company Information</h3>
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
                <label for="siret">SIREN/SIRET:</label>
                <input type="text" id="siret"><br><br>
                <!-- Add other fields as necessary -->
            </div>

            <div class="client-info">
                <h3>Client Information</h3>
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
                <!-- Add other fields as necessary -->
            </div>
        </div>

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

        <div class="totals">
            <p>Total HT: <span id="total-ht">0.00</span></p>
            <p>Total TTC: <span id="total-ttc">0.00</span></p>
            <p>Net à payer (€): <span id="net-to-pay">0.00</span></p>
        </div>

    </div>

    <script src="script.js"></script>
</body>
</html>
