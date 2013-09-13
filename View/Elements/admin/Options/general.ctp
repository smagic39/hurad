<div class="page-header">
    <h2><?php echo $title_for_layout; ?></h2>
</div>

<?php
echo $this->Form->create(
    'Option',
    array(
        'class' => 'form-horizontal',
        'inputDefaults' => array(
            'label' => false,
            'div' => false
        ),
        'url' => array(
            'controller' => 'options',
            'action' => 'prefix',
            $prefix,
        )
    )
);
?>

<div class="control-group">
    <?php echo $this->Form->label('site_name', __d('hurad', 'Site Name'), array('class' => 'control-label')); ?>
    <div class="controls">
        <?php echo $this->Form->input('site_name', array('type' => 'text')); ?>
    </div>
</div>
<div class="control-group <?php echo $this->AdminLayout->isFieldError('General.site_url', $errors) ? 'error' : ''; ?>">
    <?php echo $this->Form->label('site_url', __d('hurad', 'Site URL'), array('class' => 'control-label')); ?>
    <div class="controls">
        <?php echo $this->Form->input('site_url', array('type' => 'text')); ?>
    </div>
</div>
<div class="control-group">
    <?php echo $this->Form->label('site_description', __d('hurad', 'Site Description'), array('class' => 'control-label')); ?>
    <div class="controls">
        <?php echo $this->Form->input('site_description', array('type' => 'text')); ?>
        <span class="help-inline"><?php echo __d('hurad', 'In a few words, explain what this site is about.') ?></span>
    </div>
</div>
<div class="control-group <?php echo $this->AdminLayout->isFieldError(
    'General.admin_email',
    $errors
) ? 'error' : ''; ?>">
    <?php echo $this->Form->label('admin_email', __d('hurad', 'Admin Email'), array('class' => 'control-label')); ?>
    <div class="controls">
        <?php echo $this->Form->input('admin_email', array('type' => 'text')); ?>
        <span class="help-inline"><?php echo __(
                'This address is used for admin purposes, like new user notification.'
            ) ?></span>
    </div>
</div>
<div class="control-group">
    <?php echo $this->Form->label('users_can_register', __d('hurad', 'Membership'), array('class' => 'control-label')); ?>
    <div class="controls">
        <?php echo $this->Form->input('users_can_register', array('type' => 'checkbox')); ?>
        <span class="help-inline"><?php echo __d('hurad', 'Anyone can register') ?></span>
    </div>
</div>
<div class="control-group">
    <?php echo $this->Form->label('default_role', __d('hurad', 'Default Role'), array('class' => 'control-label')); ?>
    <div class="controls">
        <?php
        echo $this->Form->input(
            'default_role',
            array(
                'options' => array(
                    'user' => __d('hurad', 'User'),
                    'author' => __d('hurad', 'Author'),
                    'editor' => __d('hurad', 'Editor'),
                    'adminstrator' => __d('hurad', 'Adminstrator'),
                ),
            )
        );
        ?>
    </div>
</div>
<div class="control-group">
    <?php echo $this->Form->label('timezone', __d('hurad', 'Timezone'), array('class' => 'control-label')); ?>
    <div class="controls">
        <?php echo $this->Form->select('timezone', $this->Time->listTimezones()); ?>
        <span class="help-inline"><?php echo __d('hurad', 'Choose a city in the same timezone as you.'); ?></span>
    </div>
</div>
<div class="control-group">
    <?php echo $this->Form->label('date_format', __d('hurad', 'Date Format'), array('class' => 'control-label')); ?>
    <div class="controls">
        <?php
        echo $this->Form->input(
            'date_format',
            array(
                'options' => array(
                    'F j, Y' => $this->Time->format('F j, Y', time(), null, Configure::read('General.timezone')),
                    'Y/m/d' => $this->Time->format('Y/m/d', time(), null, Configure::read('General.timezone')),
                    'm/d/Y' => $this->Time->format('m/d/Y', time(), null, Configure::read('General.timezone')),
                    'd/m/Y' => $this->Time->format('d/m/Y', time(), null, Configure::read('General.timezone'))
                ),
            )
        );
        ?>
    </div>
</div>
<div class="control-group">
    <?php echo $this->Form->label('time_format', __d('hurad', 'Time Format'), array('class' => 'control-label')); ?>
    <div class="controls">
        <?php
        echo $this->Form->input(
            'time_format',
            array(
                'options' => array(
                    'g:i a' => $this->Time->format('g:i a', time(), null, Configure::read('General.timezone')),
                    'g:i A' => $this->Time->format('g:i A', time(), null, Configure::read('General.timezone')),
                    'H:i' => $this->Time->format('H:i', time(), null, Configure::read('General.timezone'))
                ),
            )
        );
        ?>
    </div>
</div>

<div class="form-actions">
    <?php echo $this->Form->button(__d('hurad', 'Update'), array('type' => 'submit', 'class' => 'btn btn-primary')); ?>
</div>

<?php echo $this->Form->end(); ?>