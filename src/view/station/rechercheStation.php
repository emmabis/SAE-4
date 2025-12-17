<body class='recherche-body'>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<form method="post" action="../web/frontController.php?action=dataMeteo&controller=station" id="station-form">
    <fieldset class="form-container">
        <legend class="form-title">Recherche de station :</legend>
        <p class="form-group">
            <label for="station_id" class="form-label">ID Station</label>
            <input type="text" placeholder="Entrez l'ID de la station" name="station_id" id="station_id" class="form-input" required />
        </p>
        <p class="form-group">
            <input type="submit" value="Envoyer" class="form-submit" />
        </p>
    </fieldset>
</form>
<body>