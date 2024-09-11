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
<div class="conttainer_signup">



    <div class="container">



        <!-- Le formulaire est envoye au fichier signup_process.php qui traitera l'inscription.-->

            <form class="form_signup" action="signup_process.php" method="POST" enctype="multipart/form-data">
    
            <label for="username">Pseudo :</label>
            <input type="text" id="username" name="username" pattern="[a-zA-Z0-9_]+" required maxlength="12">

            <label for="avatar">Avatar :</label>
            <input type="file" id="avatar" name="avatar" accept="image/*" >
            
            <label for="age">Âge :</label>
            <input type="number" id="age" name="age" required>




  <!-- Champ de saisie pour le département (type tel) -->
  <label for="department">Numéro de Département :</label>
        <input type="tel" id="department_id" name="department" required pattern="[0-9]{2,5}" maxlength="5">

         <!-- Sélection de la ville -->
    <div id="city-container" style="display:none;">
        <label for="city">Ville :</label>
        <select id="ville_dpt" name="ville_users" required>
            <option value="">Sélectionnez une ville</option>
        </select>
    </div>


            <div class="gender">

<input type="radio" id="homme" name="gender" value="male">
<label for="homme">Homme</label>

<input type="radio" id="femme" name="gender" value="female">
<label for="femme">Femme</label>
</div>





            <button type="submit">Entrée</button>
            </form>


    </div> 
    <!-- fin conttainer -->

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
