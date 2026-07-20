<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Insertion multiple<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <h2>Insertion multiple (même opérateur)</h2>

    <p class="text-muted">
        Le montant total est divisé équitablement entre tous les destinataires.
        Si un destinataire appartient à un autre opérateur, une commission de 10%
        sera automatiquement appliquée sur son envoi (le frais de retrait ne
        s'applique alors pas pour ce destinataire).
    </p>

    <form method="post" action="<?= base_url('client/operationmultiple') ?>" id="formInsertionMultiple" class="form-wide">

        <!-- Montant total -->
        <div class="form-group">
            <label>Montant total à répartir :</label>
            <input
                type="number"
                name="montant"
                id="montant"
                value="<?= old('montant', $montant ?? '') ?>"
                required
            >
        </div>

        <!-- Code client -->
        <div class="form-group">
            <label>Code client :</label>
            <input
                type="text"
                name="code"
                value="<?= old('code', $client['code'] ?? '') ?>"
                required
            >
        </div>

        <!-- Numéro client -->
        <div class="form-group">
            <label>Numéro client :</label>
            <input
                type="text"
                name="numero"
                value="<?= old('numero', $client['numero'] ?? '') ?>"
                required
            >
        </div>

        <!-- Liste des destinataires -->
        <div class="form-group">
            <label>Destinataires :</label>

            <div id="destinatairesContainer">
                <div class="destinataire-ligne">
                    <input
                        type="text"
                        name="numero_destinataire[]"
                        placeholder="Numéro destinataire"
                        required
                    >
                    <button type="button" class="btn btn-danger btn-sm btn-supprimer-ligne">
                        Supprimer
                    </button>
                </div>
            </div>

            <button type="button" class="btn btn-outline btn-sm" id="btnAjouterDestinataire">
                + Ajouter un destinataire
            </button>
        </div>

        <!-- Frais de retrait du destinataire inclus dans le montant envoyé -->
        <div class="form-group" id="blocInclureFrais">
            <label>
                <input
                    type="checkbox"
                    id="inclureFraisCheckbox"
                    checked
                >
                Inclure le frais de retrait du destinataire dans le montant saisi
            </label>
        </div>

        <input type="hidden" name="inclure_frais" id="inclure_frais" value="1">

        <!-- Toujours un transfert vers le même opérateur -->
        <input type="hidden" name="meme_operateur" value="1">

        <!-- Type opération caché -->
        <input
            type="hidden"
            name="id_type_operation"
            id="id_type_operation"
            value="<?= $id_type_operation ?>"
        >

        <div id="transfertInfo" class="frais-info"></div>

        <div class="card" id="totauxCard">
            <h3>Totaux</h3>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Nombre de destinataires</div>
                    <div class="stat-value" id="totalDestinataires">0</div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">Total frais de transfert</div>
                    <div class="stat-value" id="totalFraisTransfert">0 Ar</div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">Total frais de retrait destinataire</div>
                    <div class="stat-value" id="totalFraisRetrait">0 Ar</div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">Total prélevé</div>
                    <div class="stat-value" id="totalPreleve">0 Ar</div>
                </div>
            </div>
        </div>

        <button type="submit">Valider</button>

    </form>
</div>

<script>

(function () {
    var montantInput = document.getElementById('montant');
    var typeOperationInput = document.getElementById('id_type_operation');
    var timer = null;

    var solde = <?= json_encode($solde ?? 0) ?>;

    var ID_TYPE_OPERATION_RETRAIT = 2;

    function recupererFrais(idTypeOperation, montant) {
        var url = '<?= base_url('client/frais') ?>'
            + '?id_type_operation=' + encodeURIComponent(idTypeOperation)
            + '&montant=' + encodeURIComponent(montant);

        return fetch(url).then(function (response) { return response.json(); });
    }

    /* ------------------------------------------------------------------ */
    /* Gestion dynamique de la liste des destinataires                    */
    /* ------------------------------------------------------------------ */

    var destinatairesContainer = document.getElementById('destinatairesContainer');
    var btnAjouterDestinataire = document.getElementById('btnAjouterDestinataire');

    function creerLigneDestinataire() {
        var ligne = document.createElement('div');
        ligne.className = 'destinataire-ligne';

        var input = document.createElement('input');
        input.type = 'text';
        input.name = 'numero_destinataire[]';
        input.placeholder = 'Numéro destinataire';
        input.required = true;
        input.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(verifierTransfert, 400);
        });

        var btnSupprimer = document.createElement('button');
        btnSupprimer.type = 'button';
        btnSupprimer.className = 'btn btn-danger btn-sm btn-supprimer-ligne';
        btnSupprimer.textContent = 'Supprimer';
        btnSupprimer.addEventListener('click', function () {
            ligne.remove();
            verifierTransfert();
        });

        ligne.appendChild(input);
        ligne.appendChild(btnSupprimer);

        return ligne;
    }

    btnAjouterDestinataire.addEventListener('click', function () {
        destinatairesContainer.appendChild(creerLigneDestinataire());
        verifierTransfert();
    });

    destinatairesContainer.addEventListener('click', function (event) {
        if (event.target.classList.contains('btn-supprimer-ligne')) {
            event.target.closest('.destinataire-ligne').remove();
            verifierTransfert();
        }
    });

    destinatairesContainer.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(verifierTransfert, 400);
    });

    function getNombreDestinataires() {
        return destinatairesContainer.querySelectorAll('input[name="numero_destinataire[]"]').length;
    }

    /* ------------------------------------------------------------------ */
    /* Frais de retrait inclus ou non                                     */
    /* ------------------------------------------------------------------ */

    var inclureFraisCheckbox = document.getElementById('inclureFraisCheckbox');
    var inclureFraisInput = document.getElementById('inclure_frais');

    var transfertInfo = document.getElementById('transfertInfo');
    var submitButton = document.querySelector('button[type="submit"]');

    var totalDestinatairesEl = document.getElementById('totalDestinataires');
    var totalFraisTransfertEl = document.getElementById('totalFraisTransfert');
    var totalFraisRetraitEl = document.getElementById('totalFraisRetrait');
    var totalPreleveEl = document.getElementById('totalPreleve');

    function afficherTransfertMessage(text, type) {
        transfertInfo.textContent = text;
        transfertInfo.className = 'frais-info' + (type ? ' frais-info-' + type : '');
    }

    function majInclureFraisInput() {
        inclureFraisInput.value = inclureFraisCheckbox.checked ? '1' : '0';
    }

    function verifierTransfert() {
        var montantTotal = parseFloat(montantInput.value);
        var idTypeOperation = typeOperationInput.value;
        var nombreDestinataires = getNombreDestinataires();

        majInclureFraisInput();

        if (!montantTotal || isNaN(montantTotal) || nombreDestinataires === 0) {
            afficherTransfertMessage('', '');
            totalDestinatairesEl.textContent = nombreDestinataires;
            totalFraisTransfertEl.textContent = '0 Ar';
            totalFraisRetraitEl.textContent = '0 Ar';
            totalPreleveEl.textContent = '0 Ar';
            return;
        }

        var montantParDestinataire = montantTotal / nombreDestinataires;

        var fraisTransfertPromise = recupererFrais(idTypeOperation, montantParDestinataire);
        var fraisRetraitPromise = recupererFrais(ID_TYPE_OPERATION_RETRAIT, montantParDestinataire);

        Promise.all([fraisTransfertPromise, fraisRetraitPromise])
            .then(function (results) {
                var dataTransfert = results[0];
                var dataRetrait = results[1];

                var fraisTransfert = dataTransfert.found ? Number(dataTransfert.montant) : 0;
                var fraisRetrait = dataRetrait.found ? Number(dataRetrait.montant) : 0;

                var inclureFraisRetrait = inclureFraisCheckbox.checked;

                // Montant envoyé (stocké) au destinataire : le montant par destinataire
                // reste inchangé, mais si la case est cochée, le frais de retrait
                // du destinataire est prélevé en plus sur le solde de l'expéditeur
                // (pour que le destinataire reçoive net le montant par destinataire
                // après avoir payé son propre frais de retrait).
                var montantEnvoyeParDestinataire = montantParDestinataire;
                var totalParDestinataire;

                if (inclureFraisRetrait) {
                    totalParDestinataire = montantParDestinataire + fraisTransfert + fraisRetrait;
                } else {
                    totalParDestinataire = montantParDestinataire + fraisTransfert;
                }

                var totalFraisTransfert = fraisTransfert * nombreDestinataires;
                var totalFraisRetrait = inclureFraisRetrait ? (fraisRetrait * nombreDestinataires) : 0;
                var totalPreleve = totalParDestinataire * nombreDestinataires;

                totalDestinatairesEl.textContent = nombreDestinataires;
                totalFraisTransfertEl.textContent = totalFraisTransfert + ' Ar';
                totalFraisRetraitEl.textContent = totalFraisRetrait + ' Ar';
                totalPreleveEl.textContent = totalPreleve + ' Ar';

                var message = 'Montant par destinataire (envoyé) : ' + montantEnvoyeParDestinataire.toFixed(2) + ' Ar'
                    + ' - Frais de transfert (par destinataire) : ' + fraisTransfert + ' Ar';

                if (inclureFraisRetrait) {
                    message += ' - Frais de retrait destinataire (par destinataire) : ' + fraisRetrait + ' Ar';
                }

                if (totalPreleve > solde) {
                    afficherTransfertMessage(message + ' - Solde insuffisant (solde actuel : ' + solde + ' Ar).', 'error');
                    if (submitButton) { submitButton.disabled = true; }
                } else {
                    afficherTransfertMessage(message, 'success');
                    if (submitButton) { submitButton.disabled = false; }
                }
            })
            .catch(function () {
                afficherTransfertMessage('Erreur lors du calcul du transfert.', 'error');
            });
    }

    montantInput.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(verifierTransfert, 400);
    });

    inclureFraisCheckbox.addEventListener('change', function () {
        majInclureFraisInput();
        verifierTransfert();
    });

    majInclureFraisInput();

    if (montantInput.value) {
        verifierTransfert();
    }
})();
</script>

<?= $this->endSection() ?>
