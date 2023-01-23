<?php
// Initialize form fields
$form_fields = array(
    [
        'required' => true,
        'id' => 'name',
        'label' => esc_html__('Vaše jméno', 'nddb'),
        'type' => 'text',
        'class' => 'row-cols-2',
    ],
    [
        'required' => true,
        'id' => 'email',
        'label' => esc_html__('Váš e-mail', 'nddb'),
        'type' => 'email',
        'class' => 'row-cols-2',
    ],
    [
        'required' => true,
        'id' => 'nazev_zaznamu',
        'label' => esc_html__('Název záznamu', 'nddb'),
        'type' => 'text',
        'class' => 'full-width',
    ],
    [
        'required' => false,
        'id' => 'podtitul',
        'label' => esc_html__('Podtitul', 'nddb'),
        'type' => 'text',
        'class' => 'row-cols-2',
    ],
    [
        'required' => false,
        'id' => 'zanr',
        'label' => esc_html__('Žánr', 'nddb'),
        'type' => 'text',
        'class' => 'row-cols-2',
    ],
    [
        'required' => false,
        'id' => 'matricni_cislo',
        'label' => esc_html__('Matriční číslo', 'nddb'),
        'type' => 'text',
        'class' => 'row-cols-2',
    ],
    [
        'required' => false,
        'id' => 'objednaci_cislo',
        'label' => esc_html__('Objednací číslo', 'nddb'),
        'type' => 'text',
        'class' => 'row-cols-2',
    ],
    [
        'required' => false,
        'id' => 'label',
        'label' => esc_html__('Label', 'nddb'),
        'type' => 'text',
        'class' => 'row-cols-2',
    ],
    [
        'required' => false,
        'id' => 'reedice',
        'label' => esc_html__('Reedice', 'nddb'),
        'type' => 'text',
        'class' => 'row-cols-2',
    ],
    [
        'required' => false,
        'id' => 'typ_nosice',
        'label' => esc_html__('Typ nosiče', 'nddb'),
        'type' => 'text',
        'class' => 'row-cols-2',
    ],
    [
        'required' => false,
        'id' => 'fyzicke_parametry',
        'label' => esc_html__('Fyzické parametry', 'nddb'),
        'type' => 'text',
        'class' => 'row-cols-2',
    ],
    [
        'required' => false,
        'id' => 'misto_vydani',
        'label' => esc_html__('Místo vydání', 'nddb'),
        'type' => 'text',
        'class' => 'row-cols-2',
    ],
    [
        'required' => false,
        'id' => 'datum_vydani',
        'label' => esc_html__('Datum vydání', 'nddb'),
        'type' => 'text',
        'class' => 'row-cols-2',
    ],
    [
        'required' => false,
        'id' => 'vlastnik_nahravky',
        'label' => esc_html__('Vlastník nahrávky', 'nddb'),
        'type' => 'text',
        'class' => 'row-cols-2',
    ],
    [
        'required' => false,
        'id' => 'dedikace',
        'label' => esc_html__('Dedikace', 'nddb'),
        'type' => 'text',
        'class' => 'row-cols-2',
    ],
    [
        'required' => false,
        'id' => 'autor',
        'label' => esc_html__('Autor', 'nddb'),
        'type' => 'text',
        'class' => 'row-cols-2',
    ],
    [
        'required' => false,
        'id' => 'hudba',
        'label' => esc_html__('Hudba', 'nddb'),
        'type' => 'text',
        'class' => 'row-cols-2',
    ],
    [
        'required' => false,
        'id' => 'dirigent',
        'label' => esc_html__('Dirigent', 'nddb'),
        'type' => 'text',
        'class' => 'row-cols-2',
    ],
    [
        'required' => false,
        'id' => 'interpret',
        'label' => esc_html__('Interpret', 'nddb'),
        'type' => 'text',
        'class' => 'row-cols-2',
    ],
    [
        'required' => false,
        'id' => 'nastroje_hlasy',
        'label' => esc_html__('Nástroje/hlasy', 'nddb'),
        'type' => 'text',
        'class' => 'row-cols-2',
    ],
    [
        'required' => false,
        'id' => 'teleso',
        'label' => esc_html__('Těleso', 'nddb'),
        'type' => 'text',
        'class' => 'row-cols-2',
    ],
);

// GDPR Checkbox field
$gdpr_checkbox_field = [
    'required' => true,
    'id' => 'gdpr',
    'label' => sprintf(esc_html__('Souhlasím s podmínkami %s ochrany osobních údajů.%s', 'nddb'), '<a class="body-link" href="' . get_privacy_policy_url() . '" target="_blank">', '</a>'),
    'type' => 'checkbox',
];

?>

<script>
// Translatable error messages
const formMessages = {
    successfulSubmit: '<?= esc_html__('Zpráva byla úspěšně odeslána.', 'nddb') ?>', 
    errorSubmit: '<?= esc_html__('Před odesláním formuláře opravte chyby.', 'nddb') ?>', 
    required: {
        default: '<?= esc_html__('Toto pole je povinné.', 'nddb') ?>',
        select: '<?= esc_html__('Výběr je povinný.', 'nddb') ?>',
        email: '<?= esc_html__('E-mail je povinný.', 'nddb') ?>',
        tel: '<?= esc_html__('Telefon je povinný.', 'nddb') ?>',
        textarea: '<?= esc_html__('Zpráva je povinná.', 'nddb') ?>',
        gdpr: '<?= esc_html__('Souhlas je povinný.', 'nddb') ?>',
        file: '<?= esc_html__('Soubor je povinný.', 'nddb') ?>'
    },
    email: '<?= esc_html__('E-mail nemá správný tvar.', 'nddb') ?>',
};
</script>


<form class="contact-form" data-has-validation>
    <div class="form-fields z-0 mt-6">
        <?php // Create a form field for each entry in the array.
        foreach($form_fields as $field): ?>
            <?= createField($field) ?>
        <?php endforeach; ?>
        <div class="field full-width">
        <label>Etiketa</label>
        </div>
        <div class="field full-width">
        <input type="file" id="etiketa" name="etiketa" placeholder=" ">
        </div>
    </div>
    <div class="d-flex flex-wrap justify-content-between align-items-center w-100 mt-4 gap-4">
        <?= createField($gdpr_checkbox_field) ?>
        <button class="btn btn-primary">
            <?= esc_html__('Odeslat formulář', 'nddb') ?>
            <?= get_template_part( 'templates/components/svg/icon', 'chevron-right' ) ?>
        </button>
    </div>
    <div class="form-loader" data-form-loader>
        <div class="form-spinner d-flex justify-content-center align-items-center flex-grow-1" data-form-spinner>
            <svg viewBox="24 24 48 48">
                <circle cx="48" cy="48" r="20" fill="none" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10" />
            </svg>
        </div>
        <div class="form-check d-flex justify-content-center align-items-center flex-grow-1">
            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                fill="none" stroke-linecap="round" stroke-linejoin="round">
                <polyline stroke-dashoffset="-64" stroke-dasharray="64" points="20 6 9 17 4 12">
                </polyline>
            </svg>
        </div>
        <div class="form-x d-flex justify-content-center align-items-center flex-grow-1">
            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <line stroke-dashoffset="-64" stroke-dasharray="64" x1="18" y1="6" x2="6" y2="18"></line>
                <line stroke-dashoffset="64" stroke-dasharray="64" x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </div>
    </div>
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.2/jquery.min.js" integrity="sha512-tWHlutFnuG0C6nQRlpvrEhE4QpkG1nn2MOUMWmUeRePl4e3Aki0VB6W1v3oLjFtd0hVOtRQ9PHpSfN6u6/QXkQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
jQuery(document).ready(function() {
    <?php
    foreach ($form_fields as $index => $settings) {
        echo 'jQuery("#'.$settings['id'].'").val(jQuery("#'.$settings['id'].'_val").text());';
    }
    ?>
});
</script>
