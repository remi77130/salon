<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="style/index.css">

    <meta name="description" content="Tchat en direct, gratuit et sans inscription. Rencontres ou convivialité, tu n'as qu'à choisir la salle qui t'intéresse et passer du bon temps avec nous :) Bon chat !" /> 
	<meta name="keywords" content="tchat, chat, tchat en direct, t'chat, tchat gratuit" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

   
</head>
<body>  
<h1>Bienvenue, commencer à tchatter 
</h1>
    <div class="login-container">
        <div class="login-box">

            <h2>Login</h2>
            <form action="signup_process.php" method="post">

                <div class="input-box">
               
                <input placeholder="Pseudo" type="text" id="username" name="username" pattern="[a-zA-Z0-9_]+" required maxlength="12">
                </div>

                <div class="input-box">
                <input type="file" id="avatar" name="avatar" accept="image/*" >
                </div>



                <div class="input-box">
            <input placeholder="Age" type="number" id="age" name="age" required>

                </div>



  <!-- Champ de saisie pour le département (type tel) -->
  <div class="input-box">
  <label for="department">Numéro de Département :</label>
        <input type="tel" id="department_id" name="department" required pattern="[0-9]{2,5}" maxlength="5">
</div>
         <!-- Sélection de la ville -->
         <div class="input-box">

    <div id="city-container" style="display:none;">
        <label for="city">Ville :</label>
        <select id="ville_dpt" name="ville_users" required>
            <option value="">Sélectionnez une ville</option>
        </select>
    </div>
</div>



<div class="input-box">
<input type="radio" id="homme" name="gender" value="male">
<label for="homme">Homme</label>

                </div>


                
                <div class="input-box">
                <input type="radio" id="femme" name="gender" value="female">
                <label for="femme">Femme</label>

                </div>









                <button type="submit" class="btn-submit">Submit</button>
            </form>
            <div class="extra-links">
                <a href="#">Register</a>
                <a href="#">Forgot Password</a>
            </div>
        </div>
        <div class="image-overlay">
            <img src="img/coco_logo(1).svg" alt="Person Illustration">
        </div>
    </div>









    <script>
    $(document).ready(function() {
        $('#department_id').on('input', function() {
            var departmentId = $(this).val().trim();

            if (departmentId.length > 0) {
                $.ajax({
                    url: 'get_cities.php',
                    method: 'GET',
                    data: { department_id: departmentId },
                    success: function(data) {
                        var citySelect = $('#ville_dpt');
                        citySelect.empty();
                        citySelect.append('<option value="">Sélectionnez une ville</option>');

                        if (data.length > 0) {
                            data.forEach(function(city) {
                                citySelect.append('<option value="' + city.ville + '">' + city.ville + '</option>');
                            });
                            $('#city-container').show();
                            citySelect.attr('required', true);
                        } else {
                            $('#city-container').hide();
                            citySelect.removeAttr('required');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Erreur AJAX : ", status, error);
                    }
                });
            } else {
                $('#city-container').hide();
                $('#ville_dpt').removeAttr('required');
            }
        });
    });

</script>
</body>
</html>
