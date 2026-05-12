<?php
class Vue {
    public function entete() {
        echo "
        <!DOCTYPE html>
        <html lang='fr'>
        <head>
            <meta charset='UTF-8'>
            <title>FoodTruck</title>
            <link rel='stylesheet' href='./css/style.css'>
        </head>
        <body>
            <header>
                <h1 class='site-title'>Bienvenue sur la carte des FoodTruck</h1>
                <nav class='main-nav'>
                    <a href='index.php?action=accueil'>Accueil</a> |";
        
            if (!isset($_SESSION['connexion'])) {
                echo "
                    <a href='index.php?action=connexion'>Connexion</a> |
                    <a href='index.php?action=inscription'>Inscription</a>";
            } 
            else {
            echo "
                    <a href='index.php?action=formulaireChangerMdp'>Changer mot de passe</a> |
                    <a href='index.php?action=deconnexion'>Déconnexion</a>";
            }
        echo "
                </nav>
            </header>
            <main>
        ";
    }

    private function fin() {
        echo "
            </main>
        </body>
        </html>
        ";
    }

    public function accueil() {
        $this->entete();
        echo "<div class='contenu'>
                <h2 class='page-title'>Bienvenue sur le site des FoodTrucks</h2>
                <img src='images/ft.avif' alt='FoodTruck' class='accueil-img'>
                <p class='center-text' style='text-align : center'>
                    <a href='index.php?action=inscription'>Inscrivez-vous</a> ou 
                    <a href='index.php?action=connexion'>Connectez-vous</a>
                </p>
              </div>";
        $this->fin();
    }

    public function erreur404() {
        $this->entete();
        echo "<div class='contenu'>
                <h2 class='error-title'>Erreur 404</h2>
                <p class='center-text'>La page que vous recherchez n'existe pas ou a été supprimée.</p>
                <p class='center-text'><a href='index.php?action=accueil'>Retour à l'accueil</a></p>
              </div>";
        $this->fin();
    }

    public function inscription() {
        $this->entete();
        echo "<div class='contenu'>
                <h2 class='page-title'>Inscription</h2>
                <form action='index.php?action=inscription' method='POST' class='form'>
                    <label for='nom'>Nom :</label>
                    <input type='text' name='nom' id='nom' required>

                    <label for='prenom'>Prénom :</label>
                    <input type='text' name='prenom' id='prenom' required>

                    <label for='email'>Email :</label>
                    <input type='email' name='email' id='email' required>

                    <label for='telephone'>Téléphone :</label>
                    <input type='text' name='telephone' id='telephone' required>

                    <label for='mdp'>Mot de passe :</label>
                    <input type='password' name='mdp' id='mdp' required>

                    <label for='mdp2'>Confirmez le mot de passe :</label>
                    <input type='password' name='mdp2' id='mdp2' required>

                    <label for='role'>Vous êtes :</label>
                    <select name='role' id='role' required onchange='toggleFields()'>
                        <option value=''>-- Sélectionnez --</option>
                        <option value='client'>Client</option>
                        <option value='vendeur'>Vendeur</option>
                    </select>

                    <div id='clientFields' class='extra-fields'>
                        <label for='localisationClient'>Localisation (ville) :</label>
                        <input type='text' name='localisationClient' id='localisationClient'>
                    </div>

                    <div id='vendeurFields' class='extra-fields'>
                        <label for='nomFoodTruck'>Nom du FoodTruck :</label>
                        <input type='text' name='nomFoodTruck' id='nomFoodTruck'>
                    </div>

                    <button type='submit' class='btn-submit'>S'inscrire</button>
                </form>

                <script>
                    function toggleFields() {
                        var role = document.getElementById('role').value;
                        document.getElementById('clientFields').style.display = role === 'client' ? 'block' : 'none';
                        document.getElementById('vendeurFields').style.display = role === 'vendeur' ? 'block' : 'none';
                    }
                </script>
              </div>";
        $this->fin();
    }

    public function connexion() {
        $this->entete();
        echo "<div class='contenu'>
                <h2 class='page-title'>Connexion</h2>
                <form method='POST' action='index.php?action=connexion' class='form'>
                    <label for='email'>Adresse email</label>
                    <input type='email' name='email' id='email' required>

                    <label for='mdp'>Mot de passe</label>
                    <input type='password' name='mdp' id='mdp' required>

                    <button type='submit' class='btn-submit'>Connexion</button>
                </form>
                <p class='center-text'>Pas encore inscrit ? <a href='index.php?action=inscription'>Inscrivez-vous ici</a></p>
              </div>";
        $this->fin();
    }

    public function formulaireChangerMdp() {
        $this->entete();
        echo "<div class='contenu'>
                <h2 class='page-title'>Changer mon mot de passe</h2>
                <form method='POST' action='index.php?action=changerMdp' class='form'>
                    <label for='ancienMdp'>Ancien mot de passe</label>
                    <input type='password' name='ancienMdp' id='ancienMdp' required>

                    <label for='nouveauMdp'>Nouveau mot de passe</label>
                    <input type='password' name='nouveauMdp' id='nouveauMdp' minlength='8' required>

                    <label for='confirmationMdp'>Confirmer le nouveau mot de passe</label>
                    <input type='password' name='confirmationMdp' id='confirmationMdp' minlength='8' required>

                    <button type='submit' class='btn-submit'>Modifier le mot de passe</button>
                </form>
              </div>";
        $this->fin();
    }

    public function pageAdmin($vendeurs) {
        $this->entete();
        echo "<div class='admin-container'>
                <h2 class='page-title'>Espace Administrateur</h2>
                <p class='center-text' style='font-weight: bold;'>Validation des vendeurs :</p>";

        if (empty($vendeurs)) {
            echo "<p class='center-text no-vendeur'>Aucun vendeur en attente.</p>";
        } 
        else {
            echo "<table class='admin-table'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>";
            foreach ($vendeurs as $v) {
                $idV = $v['idV'] ?? $v['idUtilisateur'];
                echo "<tr>
                        <td>$idV</td>
                        <td>{$v['nom']}</td>
                        <td>{$v['prenom']}</td>
                        <td>{$v['email']}</td>
                        <td class='status {$v['statut']}'>{$v['statut']}</td>
                        <td class='actions'>";
                if ($v['statut'] === 'en_attente') {
                    echo "<a class='btn validate' href='index.php?action=validerVendeur&idV=$idV'>Valider</a>
                          <a class='btn refuse' href='index.php?action=refuserVendeur&idV=$idV'>Refuser</a>";
                } 
                else {
                    echo ucfirst('-');
                }
                echo "</td></tr>";
            }
            echo "</tbody></table>";
        }

        echo "</div>";
        $this->fin(); 
    }

    public function pageVendeur($estValide, $presences, $lieux = [], $horairesHebdo = []) {
        $this->entete();
        $jours = [
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            7 => 'Dimanche'
        ];

        echo "<div class='vendeur-container'>";
        echo "<h2>Espace Vendeur</h2>";

        if (!$estValide) {
            echo "<p class='alerte'>Votre compte est en attente de validation par un administrateur.</p>";
        } else {
            echo "<section class='table-section'>";
            echo "<section class='actions-section' style='text-align:center;margin-bottom:20px;'>
                    <a href='index.php?action=formulaireAjouterHoraireHebdo' class='btn-submit'>Enregistrer un horaire hebdomadaire</a>
                </section>";

            if (empty($horairesHebdo)) {
                echo "<p class='no-data'>Aucun horaire hebdomadaire enregistr&eacute;.</p>";
            } else {
                echo "<table class='table-vendeur'>
                        <thead>
                            <tr>
                                <th>Jour</th>
                                <th>Heure d'arriv&eacute;e</th>
                                <th>Heure de d&eacute;part</th>
                                <th>Lieu</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>";

                foreach ($horairesHebdo as $horaire) {
                    $jourLabel = $jours[(int) $horaire['jourSemaine']] ?? 'Jour inconnu';
                    echo "<tr>
                            <td>{$jourLabel}</td>
                            <td>{$horaire['arrive']}</td>
                            <td>{$horaire['depart']}</td>
                            <td>{$horaire['rue']}, {$horaire['cp']} {$horaire['ville']}</td>
                            <td>
                                <a href='index.php?action=formulaireModifierHoraireHebdo&id={$horaire['idHoraire']}' style='color:blue;'>Modifier</a>
                                <a href='index.php?action=supprimerHoraireHebdo&id={$horaire['idHoraire']}'
                                   onclick='return confirm(\"Supprimer cet horaire hebdomadaire ?\")'
                                   style='color:red;'>Supprimer</a>
                            </td>
                        </tr>";
                }

                echo "</tbody></table>";
            }

            echo "</section>";
        }
        echo "</div>";
        $this->fin();
    }

    public function formulairePresence($lieux = []) {
        $this->entete();

        echo "<div class='form-container'>";
        echo "<h2>Ajouter une présence</h2>";

        echo "<form method='POST' action='index.php?action=ajouterPresence' class='form-vendeur'>
                <label for='idLieu'>Sélectionnez un lieu :</label>
                <select name='idLieu' id='idLieu' required>
                    <option value=''>-- Choisissez un lieu --</option>";

        foreach ($lieux as $lieu) {
            echo "<option value='{$lieu['idLieu']}'>{$lieu['rue']}, {$lieu['cp']} {$lieu['ville']}, {$lieu['coordLat']}, {$lieu['coordLong']}</option>";
        }

        echo "</select>
            <div style='margin: 10px 0; text-align:right;'>
                <a href='index.php?action=ajouterLieu' class='btn-submit'>+ Ajouter un lieu</a>
            </div>

            <label for='date'>Date :</label>
            <input type='date' name='date' id='date' required>

            <label for='arrive'>Heure d'arrivée :</label>
            <input type='time' name='arrive' id='arrive' required>

            <label for='depart'>Heure de départ :</label>
            <input type='time' name='depart' id='depart' required>

            <button type='submit' class='btn-submit'>Enregistrer la présence</button>
            </form>";

        echo "<p style='text-align:center;margin-top:20px;'>
                <a href='index.php?action=vendeur'>← Retour à vos présences</a>
            </p>";

        echo "</div>";

        $this->fin();
    }

    public function formulaireAjouterHoraireHebdo($lieux = []) {
        $this->entete();
        $jours = [
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            7 => 'Dimanche'
        ];

        echo "<div class='form-container'>";
        echo "<h2>Ajouter un horaire hebdomadaire</h2>";
        echo "<form method='POST' action='index.php?action=ajouterHoraireHebdo' class='form-vendeur'>
                <label for='jourSemaine'>Jour :</label>
                <select name='jourSemaine' id='jourSemaine' required>
                    <option value=''>-- Choisissez un jour --</option>";

        foreach ($jours as $numero => $libelle) {
            echo "<option value='{$numero}'>{$libelle}</option>";
        }

        echo "</select>
                <label for='idLieu'>Lieu :</label>
                <select name='idLieu' id='idLieu' required>
                    <option value=''>-- Choisissez un lieu --</option>";

        foreach ($lieux as $lieu) {
            echo "<option value='{$lieu['idLieu']}'>{$lieu['rue']}, {$lieu['cp']} {$lieu['ville']}</option>";
        }

        echo "</select>
                <div style='margin: 10px 0; text-align:right;'>
                    <a href='index.php?action=ajouterLieu&return=formulaireAjouterHoraireHebdo' class='btn-submit'>+ Ajouter un lieu</a>
                </div>
                <label for='arrive'>Heure d'arriv&eacute;e :</label>
                <input type='time' name='arrive' id='arrive' required>

                <label for='depart'>Heure de d&eacute;part :</label>
                <input type='time' name='depart' id='depart' required>

                <button type='submit' class='btn-submit'>Enregistrer</button>
            </form>";

        echo "<p style='text-align:center;margin-top:20px;'>
                <a href='index.php?action=vendeur'>&larr; Retour &agrave; vos horaires</a>
            </p>";

        echo "</div>";
        $this->fin();
    }

    public function formulaireModifierHoraireHebdo($horaire, $lieux = []) {
        $this->entete();
        $jours = [
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            7 => 'Dimanche'
        ];

        echo "<div class='form-container'>";
        echo "<h2>Modifier l'horaire hebdomadaire</h2>";
        echo "<form method='POST' action='index.php?action=modifierHoraireHebdo&id={$horaire['idHoraire']}' class='form-vendeur'>
                <label for='jourSemaine'>Jour :</label>
                <select name='jourSemaine' id='jourSemaine' required>";

        foreach ($jours as $numero => $libelle) {
            $selected = ((int) $horaire['jourSemaine'] === $numero) ? 'selected' : '';
            echo "<option value='{$numero}' {$selected}>{$libelle}</option>";
        }

        echo "</select>
                <label for='idLieu'>Lieu :</label>
                <select name='idLieu' id='idLieu' required>";

        foreach ($lieux as $lieu) {
            $selected = ((int) $lieu['idLieu'] === (int) $horaire['idLieu']) ? 'selected' : '';
            echo "<option value='{$lieu['idLieu']}' {$selected}>{$lieu['rue']}, {$lieu['cp']} {$lieu['ville']}</option>";
        }

        echo "</select>
                <div style='margin: 10px 0; text-align:right;'>
                    <a href='index.php?action=ajouterLieu&return=formulaireAjouterHoraireHebdo' class='btn-submit'>+ Ajouter un lieu</a>
                </div>
                <label for='arrive'>Heure d'arriv&eacute;e :</label>
                <input type='time' name='arrive' id='arrive' value='{$horaire['arrive']}' required>

                <label for='depart'>Heure de d&eacute;part :</label>
                <input type='time' name='depart' id='depart' value='{$horaire['depart']}' required>

                <button type='submit' class='btn-submit'>Modifier l'horaire</button>
            </form>";

        echo "<p style='text-align:center;margin-top:20px;'>
                <a href='index.php?action=vendeur'>&larr; Retour &agrave; vos horaires</a>
            </p>";

        echo "</div>";
        $this->fin();
    }

    public function formulaireModifierPresence($presence, $lieux) {
        $this->entete();

        echo "<div class='form-container'>";
        echo "<h2>Modifier la présence</h2>";

        echo "<form method='POST' action='index.php?action=modifierPresence&id={$presence['idPresence']}' class='form-vendeur'>
                <label for='idLieu'>Sélectionnez un lieu :</label>
                <select name='idLieu' id='idLieu' required>
                    <option value=''>-- Choisissez un lieu --</option>";

        foreach ($lieux as $lieu) {
            $selected = ($lieu['idLieu'] == $presence['idLieu']) ? 'selected' : '';
            echo "<option value='{$lieu['idLieu']}' $selected>{$lieu['rue']}, {$lieu['cp']} {$lieu['ville']}, {$lieu['coordLat']}, {$lieu['coordLong']}</option>";
        }

        echo "</select>

            <label for='date'>Date :</label>
            <input type='date' name='date' id='date' value='{$presence['date']}' required>

            <label for='arrive'>Heure d'arrivée :</label>
            <input type='time' name='arrive' id='arrive' value='{$presence['arrive']}' required>

            <label for='depart'>Heure de départ :</label>
            <input type='time' name='depart' id='depart' value='{$presence['depart']}' required>

            <button type='submit' class='btn-submit'>Modifier la présence</button>
            </form>";

        echo "<p style='text-align:center;margin-top:20px;'>
                <a href='index.php?action=vendeur'>← Retour à vos présences</a>
            </p>";

        echo "</div>";
        $this->fin();
    }
    public function ajouterLieu($returnAction = 'formulairePresence', $villes = []) {
        $this->entete();

        $optionsVille = '';
        foreach ($villes as $ville) {
            $villeSafe = htmlspecialchars($ville, ENT_QUOTES, 'UTF-8');
            $optionsVille .= "<option value=\"{$villeSafe}\"></option>";
        }

        echo '
        <div class="form-container">
            <h2>Ajouter un lieu</h2>

            <form method="POST" action="index.php?action=ajouterLieu&amp;return=' . urlencode($returnAction) . '" class="form-vendeur">

                <label for="cp">Code postal :</label>
                <input type="text" name="cp" id="cp" required>

                <label for="ville">Ville :</label>
                <input type="text" name="ville" id="ville" list="villes-connues" required>
                <datalist id="villes-connues">' . $optionsVille . '</datalist>

                <label for="rue">Rue :</label>
                <input type="text" name="rue" id="rue" required>

                <input type="hidden" name="coordLat" id="coordLat">
                <input type="hidden" name="coordLong" id="coordLong">

                <div id="map" style="height: 400px; width: 100%; margin-top: 10px;"></div>

                <button type="submit" class="btn-submit">Enregistrer le lieu</button>
            </form>

            <p style="text-align:center;margin-top:20px;">
                <a href="index.php?action=' . htmlspecialchars($returnAction, ENT_QUOTES, 'UTF-8') . '">&larr; Retour</a>
            </p>

            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
            <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

            <script>

                const map = L.map("map").setView([48.7720, 5.1611], 13);

                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    attribution: "&copy; OpenStreetMap contributors"
                }).addTo(map);

                let marker = null;

                map.on("click", function(e) {
                    const lat = e.latlng.lat;
                    const lon = e.latlng.lng;

                    document.getElementById("coordLat").value = lat;
                    document.getElementById("coordLong").value = lon;

                    if (marker) map.removeLayer(marker);
                    marker = L.marker([lat, lon]).addTo(map);

                    fetch("index.php?action=geocodageInverse&lat=" + lat + "&lon=" + lon)
                    .then(r => r.json())
                    .then(data => {
                        if (!data.error) {
                            document.getElementById("rue").value = data.rue;
                            document.getElementById("ville").value = data.ville;
                            document.getElementById("cp").value = data.cp;
                        }
                    });
                });

            </script>
        </div>';

        $this->fin();
    }

    public function pageClient($vendeursActifs, $villeClient = null) {
        $this->entete();
        echo "<div class='client-container'>";
        echo "<h2>Carte des FoodTrucks</h2>";

        if (empty($vendeursActifs)) {
            echo "<p class='no-data'>Aucun vendeur actuellement en activité.</p>";
        } 
        else {
            echo "<div id='map' style='width:80%;height:500px;margin:auto; z-index: 1; position: relative;'></div>";

            echo '
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
            <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
            ';

            $jsVendeurs = json_encode($vendeursActifs);
            $jsVilleClient = json_encode($villeClient);

            echo <<<HTML
    <script>
        const vendeursActifs = {$jsVendeurs};
        const villeClient = {$jsVilleClient};
        const defaultCenter = [48.7720, 5.1611];
        const map = L.map('map').setView(defaultCenter, 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const bounds = [];

        vendeursActifs.forEach(vendeur => {
            const lat = parseFloat(vendeur.coordLat);
            const lon = parseFloat(vendeur.coordLong);

            if (Number.isNaN(lat) || Number.isNaN(lon)) {
                return;
            }

            bounds.push([lat, lon]);

            L.marker([lat, lon]).addTo(map)
                .bindPopup(
                    `<strong>\${vendeur.nomFoodTruck}</strong><br>` +
                    `\${vendeur.rue}, \${vendeur.cp} \${vendeur.ville}<br>` +
                    `Présent de \${vendeur.arrive} à \${vendeur.depart}`
                );
        });

        if (villeClient) {
            fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=1&q=\${encodeURIComponent(villeClient + ', France')}`)
                .then(res => res.json())
                .then(data => {
                    if (!Array.isArray(data) || data.length === 0) {
                        if (bounds.length > 0) {
                            map.fitBounds(bounds, { padding: [40, 40] });
                        }
                        return;
                    }

                    const lat = parseFloat(data[0].lat);
                    const lon = parseFloat(data[0].lon);

                    if (!Number.isNaN(lat) && !Number.isNaN(lon)) {
                        map.setView([lat, lon], 13);
                    } else if (bounds.length > 0) {
                        map.fitBounds(bounds, { padding: [40, 40] });
                    }
                })
                .catch(() => {
                    if (bounds.length > 0) {
                        map.fitBounds(bounds, { padding: [40, 40] });
                    }
                });
        } else if (bounds.length > 0) {
            map.fitBounds(bounds, { padding: [40, 40] });
        }
    </script>
    HTML;
        }
        echo "</div>";
        $this->fin();
    }
}
?>
