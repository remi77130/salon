<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="style/signup.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

   
</head>
<body>
    <div class="container">

        <h2>Inscription</h2>
        <!-- Le formulaire est envoye au fichier signup_process.php qui traitera l'inscription.-->

            <form action="signup_process.php" method="POST" enctype="multipart/form-data">
    
            <label for="username">Pseudo :</label>
            <input type="text" id="username" name="username" required>
            <label for="avatar">Avatar :</label>
            <input type="file" id="avatar" name="avatar" accept="image/*" required>
            <label for="age">Âge :</label>
            <input type="number" id="age" name="age" required>


            <label for="department">Département :</label>
            <input type="text" id="department" name="department" required>

            <div id="city-container" style="display:none;">
            <label for="city">Ville :</label>
            <select id="zip_code" name="zip_code">
            <option value="">Sélectionnez une ville</option>
            </select>
            </div>


            <label for="gender">Sexe :</label>
            <select id="gender" name="gender" required>
            <option value="male">Homme</option>
            <option value="female">Femme</option>
            <option value="other">Autre</option>
            </select>

            <button type="submit">S'inscrire</button>
            </form>
    </div>

    <script>
$(document).ready(function() {
    $('#department').on('input', function() {
        var department = $(this).val().trim(); // Utilisation de 'department'

        if (department.length > 0) {
            $.ajax({
                url: 'get_cities.php',
                method: 'GET',
                data: { department: department }, // Envoi de 'department'
                success: function(data) {
                    var citySelect = $('#zip_code');
                    citySelect.empty();
                    citySelect.append('<option value="">Sélectionnez une ville</option>');
                    
                    if (data.length > 0) {
                        data.forEach(function(city) {
                            citySelect.append('<option value="' + city.zip_code + '">' + city.label + '</option>');
                        });
                        $('#city-container').show();
                    } else {
                        $('#city-container').hide();
                    }
                }
            });
        } else {
            $('#city-container').hide();
        }
    });
});
</script>
</body>
</html>
