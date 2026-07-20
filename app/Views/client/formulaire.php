<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Effectuer une opération<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <h2>Effectuer une opération</h2>

    <form method="post" action="<?= base_url('client/operation') ?>">

        <!-- Montant -->
        <div class="form-group">
            <label>Montant :</label>
            <div id="fraisInfo" class="frais-info"></div>
            <input
                type="number"
                name="montant"
                id="montant"
                value="<?= $montant ?? '' ?>"
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

        <?php if ($transfert == true): ?>

            <!-- Numéro destinataire uniquement pour transfert -->
            <div class="form-group">
                <label>Numéro destinataire :</label>
                <input
                    type="text"
                    name="numero_destinataire"
                    value="<?= old('numero_destinataire', $destinataire['numero'] ?? '') ?>"
                    required
                >
            </div>

            <!-- Choix du type de transfert (indicatif, sert à la simulation JS -->
            <!-- ; le serveur détermine la réalité même/autre opérateur -->
            <!-- automatiquement depuis la base de données). -->
            <div class="form-group">
                <label>Type de transfert :</label>

<<<<<<< HEAD
                <label>
                    <input
                        type="radio"
                        name="meme_operateur_choix"
                        id="choixMemeOperateur"
                        value="1"
                        checked
                    >
                    Envoyer vers le même opérateur
                </label>

                <label>
                    <input
                        type="radio"
                        name="meme_operateur_choix"
                        id="choixAutreOperateur"
                        value="0"
                    >
                    Envoyer vers un autre opérateur
                </label>
            </div>

            <!-- Champ caché conservé pour compatibilité, non utilisé par le
                 serveur (qui détermine automatiquement la réalité). -->
            <input type="hidden" name="meme_operateur" id="meme_operateur" value="1">

=======
>>>>>>> 1c428d50af3f31c4a0815e6dcc2c28fea6554f73
            <!-- Frais de retrait du destinataire inclus dans le montant envoyé : uniquement pour le même opérateur -->
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

            <div id="transfertInfo" class="frais-info"></div>

        <?php endif; ?>

        <!-- Type opération caché -->
        <input
            type="hidden"
            name="id_type_operation"
            id="id_type_operation"
            value="<?= $id_type_operation ?>"
        >

        <button type="submit">Valider</button>

    </form>
</div>

<script>

(function () {
    var montantInput = document.getElementById('montant');
    var typeOperationInput = document.getElementById('id_type_operation');
    var fraisInfo = document.getElementById('fraisInfo');
    var timer = null;

    var estTransfert = <?= $transfert ? 'true' : 'false' ?>;
    var solde = <?= json_encode($solde ?? 0) ?>;

    function afficherMessage(text, type) {
        fraisInfo.textContent = text;
        fraisInfo.className = 'frais-info' + (type ? ' frais-info-' + type : '');
    }

    function recupererFrais(idTypeOperation, montant) {
        var url = '<?= base_url('client/frais') ?>'
            + '?id_type_operation=' + encodeURIComponent(idTypeOperation)
            + '&montant=' + encodeURIComponent(montant);

        return fetch(url).then(function (response) { return response.json(); });
    }

    function verifierMontant() {
        var montant = montantInput.value;
        var idTypeOperation = typeOperationInput.value;

        if (!montant) {
            afficherMessage('', '');
            return;
        }

        recupererFrais(idTypeOperation, montant)
            .then(function (data) {
                if (data.found) {
                    afficherMessage('Montant configuré pour cette opération : ' + data.montant + ' Ar', 'success');
                } else {
                    afficherMessage("La configuration pour ce montant n'existe pas.", 'error');
                }
            })
            .catch(function () {
                afficherMessage('Erreur lors de la vérification du montant.', 'error');
            });
    }

    montantInput.addEventListener('input', function () {
        clearTimeout(timer);

        timer = setTimeout(function () {
            verifierMontant();

            if (estTransfert) {
                verifierTransfert();
            }
        }, 400);
    });

    if (montantInput.value && !estTransfert) {
        verifierMontant();
    }

    if (!estTransfert) {
        return;
    }

    /* ------------------------------------------------------------------ */
    /* Logique spécifique au transfert :                                 */
    /* choix même opérateur / autre opérateur + inclure frais d'envoi     */
    /* ------------------------------------------------------------------ */

    var radioMemeOperateur = document.getElementById('choixMemeOperateur');
    var radioAutreOperateur = document.getElementById('choixAutreOperateur');
    var memeOperateurInput = document.getElementById('meme_operateur');

    var blocInclureFrais = document.getElementById('blocInclureFrais');
    var inclureFraisCheckbox = document.getElementById('inclureFraisCheckbox');
    var inclureFraisInput = document.getElementById('inclure_frais');

    var transfertInfo = document.getElementById('transfertInfo');
    var submitButton = document.querySelector('button[type="submit"]');

    function afficherTransfertMessage(text, type) {
        transfertInfo.textContent = text;
        transfertInfo.className = 'frais-info' + (type ? ' frais-info-' + type : '');
    }

    function majAffichageInclureFrais() {
        var memeOperateur = radioMemeOperateur.checked;

        memeOperateurInput.value = memeOperateur ? '1' : '0';

        // La case "inclure frais d'envoi" n'a de sens que pour le même opérateur
        blocInclureFrais.style.display = memeOperateur ? '' : 'none';

        if (!memeOperateur) {
            inclureFraisInput.value = '0';
        } else {
            inclureFraisInput.value = inclureFraisCheckbox.checked ? '1' : '0';
        }
    }

    var ID_TYPE_OPERATION_RETRAIT = 2;
    var TAUX_COMMISSION_AUTRE_OPERATEUR = 0.1;

    function verifierTransfert() {
        var montant = parseFloat(montantInput.value);
        var idTypeOperation = typeOperationInput.value;

        if (!montant || isNaN(montant)) {
            afficherTransfertMessage('', '');
            return;
        }

        // Frais du transfert lui-même (toujours prélevé en plus du montant envoyé)
        var fraisTransfertPromise = recupererFrais(idTypeOperation, montant);

        // Frais que le destinataire paierait pour retirer la somme envoyée
        var fraisRetraitPromise = recupererFrais(ID_TYPE_OPERATION_RETRAIT, montant);

        Promise.all([fraisTransfertPromise, fraisRetraitPromise])
            .then(function (results) {
                var dataTransfert = results[0];
                var dataRetrait = results[1];

                var fraisTransfert = dataTransfert.found ? Number(dataTransfert.montant) : 0;
                var fraisRetrait = dataRetrait.found ? Number(dataRetrait.montant) : 0;

                var memeOperateur = radioMemeOperateur.checked;
                var autreOperateur = !memeOperateur;

                // La case "inclure frais de retrait" n'a de sens que pour le même opérateur
                var inclureFraisRetrait = memeOperateur && inclureFraisCheckbox.checked;

                // Montant envoyé/stocké pour le destinataire (avant frais/commission)
                var montantEnvoye = inclureFraisRetrait
                    ? (montant + fraisRetrait)
                    : montant;

                // Commission appliquée uniquement pour un transfert vers un autre opérateur
                var commission = autreOperateur
                    ? Math.round(montantEnvoye * TAUX_COMMISSION_AUTRE_OPERATEUR * 100) / 100
                    : 0;

                var total = montantEnvoye + fraisTransfert + commission;

                var message = 'Montant envoyé : ' + montantEnvoye + ' Ar - Frais de transfert : ' + fraisTransfert + ' Ar';

                if (memeOperateur) {
                    message += ' - Frais de retrait destinataire : ' + fraisRetrait + ' Ar';
                } else {
                    message += ' - Commission (autre opérateur, 10%) : ' + commission + ' Ar';
                }

                message += ' - Total prélevé : ' + total + ' Ar';

                if (total > solde) {
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

    radioMemeOperateur.addEventListener('change', function () {
        majAffichageInclureFrais();
        verifierTransfert();
    });

    radioAutreOperateur.addEventListener('change', function () {
        majAffichageInclureFrais();
        verifierTransfert();
    });

    inclureFraisCheckbox.addEventListener('change', function () {
        majAffichageInclureFrais();
        verifierTransfert();
    });

    majAffichageInclureFrais();

    if (montantInput.value) {
        verifierTransfert();
    }
})();
</script>

<?= $this->endSection() ?>
