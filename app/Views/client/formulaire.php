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
                value="<?= $client['code'] ?? '' ?>"
                required
            >
        </div>

        <!-- Numéro client -->
        <div class="form-group">
            <label>Numéro client :</label>
            <input
                type="text"
                name="numero"
                value="<?= $client['numero'] ?? '' ?>"
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
                    value="<?= $destinataire['numero'] ?? '' ?>"
                    required
                >
            </div>

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

    function afficherMessage(text, type) {
        fraisInfo.textContent = text;
        fraisInfo.className = 'frais-info' + (type ? ' frais-info-' + type : '');
    }

    function verifierMontant() {
        var montant = montantInput.value;
        var idTypeOperation = typeOperationInput.value;

        if (!montant) {
            afficherMessage('', '');
            return;
        }

        var url = '<?= base_url('client/frais') ?>'
            + '?id_type_operation=' + encodeURIComponent(idTypeOperation)
            + '&montant=' + encodeURIComponent(montant);

        fetch(url)
            .then(function (response) { return response.json(); })
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
        timer = setTimeout(verifierMontant, 400);
    });

    if (montantInput.value) {
        verifierMontant();
    }
})();
</script>

<?= $this->endSection() ?>
