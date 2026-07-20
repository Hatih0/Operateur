<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Effectuer une opération<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <h2>Effectuer une opération</h2>

    <form method="post" action="<?= base_url('client/operation') ?>">

        <!-- Montant -->
        <div class="form-group">
            <label>Montant :</label>
            <input
                type="number"
                name="montant"
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

        <?php endif; ?>

        <!-- Type opération caché -->
        <input
            type="hidden"
            name="id_type_operation"
            value="<?= $id_type_operation ?>"
        >

        <button type="submit">Valider</button>

    </form>
</div>

<?= $this->endSection() ?>
