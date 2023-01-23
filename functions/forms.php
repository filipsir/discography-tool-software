<?php // Function to create a form field, including custom select.
function createField($field) {
    
    if ($field === null) return;

    // Required values
    $required = $field['required'];
    $id = $field['id'];
    $type = $field['type'];
    
    // Optional values
    $class = (isset($field['class']) ? $field['class'] : null);
    $label = (isset($field['label']) ? $field['label'] : null);
    $options = (isset($field['options']) ? $field['options'] : null);
    $value = (isset($field['value']) ? $field['value'] : null);

    // File upload values
    $max_upload_filesize = !empty($field['max_upload_filesize']) ? $field['max_upload_filesize'] : '20971520';
    $accepts_filetype = !empty($field['accepts_filetype']) ? $field['accepts_filetype'] : '*';
    
    $tag = 'input';
    if ($type === 'textarea') {
        $tag = $type;
    }
?>
<div class="field<?= $class ? " " . $class : ''?>" <?= $options ? 'data-has-options' : '' ?> <?= $required ? 'data-required' : '' ?> data-type="<?= $type ?>" <?= $type === 'select' ? 'tabindex="0"' : '' ?> <?= $type === 'file' ? "data-max-upload-filesize='{$max_upload_filesize}' data-accepts_filetype='{$accepts_filetype}'" : ''  ?> data-field>
    <<?= $tag ?> type="<?= $type === 'select' ? 'hidden' : $type ?>" <?= $type === 'select' ? 'data-type="select"' : '' ?> id="<?= $id ?>" name="<?= $id ?>" <?= $required ? 'data-required aria-required="true"' : '' ?> <?= $type === 'file' ? "accept='{$accepts_filetype}'" : ''  ?> <?= ($type === 'checkbox') ? "value='{$id}'" : '' ?><?= ($type === 'text' && isset($value) ? ' value="'.$value.'"' : '') ?>placeholder=" "></<?= $tag ?>>

    <?php if ($type === 'checkbox'): ?>
        <div class="check">
            <?= get_template_part( 'templates/components/svg/icon', 'check' ) ?>
        </div>
    <?php endif; ?>

    <?php if ($label): ?>
        <label for="<?= $id ?>"><?= $label ?></label>
    <?php endif; ?>

    <?php if ($type === 'select'): ?>
        <i data-feather="chevron-down" stroke-width="1"></i>
    <?php endif; ?>

    <?php if ($type === 'file'): ?>
        <i data-feather="upload" stroke-width="1"></i>
    <?php endif; ?>

    <?php if ($options): ?>
        <div class="options" data-select-options>
            <div class="options-wrapper">
                <?php
                foreach($options as $option):
                    $option_label = $option['label'];
                    $option_value = $option['value'];

                    // Optional
                    $option_selected = @hesti_isset($option['selected']);
                ?>
                    <div tabindex="0" role="button" class="option" data-label="<?= $option_label ?>" data-value="<?= $option_value ?>" data-option <?= $option_selected ? 'data-selected data-default-option' : '' ?>>
                        <span><?= $option_label ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($type === 'select'): ?>
        <div class="select-field">
            <span data-select-preview></span>
        </div>
    <?php endif; ?>
    <?php if ($type === 'file'): ?>
        <div class="file-upload-field">
            <span data-file-preview></span>
        </div>
    <?php endif; ?>
</div>
<?php } ?>
