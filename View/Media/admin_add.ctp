<div class="page-header">
    <h2><?php echo $title_for_layout; ?></h2>
</div>

<?php
$maxSize = ini_get('upload_max_filesize');
echo $this->Form->create(
    'Media',
    [
        'class' => 'form-horizontal',
        'inputDefaults' => [
            'label' => false,
            'div' => false
        ],
        'type' => 'file'
    ]
);
?>

<div class="form-group">
    <?=
    $this->Form->label(
        'title',
        __d('hurad', 'Tile'),
        ['class' => 'control-label col-lg-2']
    ); ?>
    <div class="col-lg-4">
        <?=
        $this->Form->input(
            'title',
            [
                'required' => false, //For disable HTML5 validation
                'class' => 'form-control'
            ]
        );
        ?>
    </div>
</div>

<div class="form-group">
    <?=
    $this->Form->label(
        'media_file',
        __d('hurad', 'Choose File'),
        ['class' => 'control-label col-lg-2']
    ); ?>
    <div class="col-lg-4">
        <?=
        $this->Form->input(
            'media_file',
            [
                'required' => false, //For disable HTML5 validation
                'type' => 'file',
                'class' => 'form-control'
            ]
        );
        ?>
    </div>
</div>

<div class="form-group">
    <?=
    $this->Form->label(
        'description',
        __d('hurad', 'Description'),
        ['class' => 'control-label col-lg-2']
    ); ?>
    <div class="col-lg-4">
        <?=
        $this->Form->input(
            'description',
            [
                'required' => false, //For disable HTML5 validation
                'class' => 'form-control'
            ]
        );
        ?>
    </div>
</div>

<?= $this->Form->hidden('MAX_FILE_SIZE', ['value' => CakeNumber::fromReadableSize($maxSize)]); ?>
<?= $this->Form->button(__d('hurad', 'Upload'), ['type' => 'submit', 'class' => 'btn btn-primary']); ?>

<?= $this->Form->end(); ?>