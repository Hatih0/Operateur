<h2>Effectuer une opération</h2>

<form method="post" action="<?= base_url('client/operation') ?>">

    <!-- Montant -->
    <label>
        Montant :
    </label>

    <input 
        type="number" 
        name="montant"
        value="<?= $montant ?? '' ?>"
        required
    >


    <br><br>


    <!-- Code client -->
    <label>
        Code client :
    </label>

    <input 
        type="text" 
        name="code"
        value="<?= $client['code'] ?? '' ?>"
        required
    >


    <br><br>


    <!-- Numéro client -->
    <label>
        Numéro client :
    </label>

    <input 
        type="text" 
        name="numero"
        value="<?= $client['numero'] ?? '' ?>"
        required
    >


    <br><br>


    <?php if ($transfert == true): ?>

        <!-- Numéro destinataire uniquement pour transfert -->

        <label>
            Numéro destinataire :
        </label>

        <input 
            type="text"
            name="numero_destinataire"
            value="<?= $destinataire['numero'] ?? '' ?>"
            required
        >

        <br><br>

    <?php endif; ?>


    <!-- Type opération caché -->

    <input 
        type="hidden"
        name="id_type_operation"
        value="<?= $id_type_operation ?>"
    >


    <button type="submit">
        Valider
    </button>

</form>x