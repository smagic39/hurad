<div class="page-header">
    <h2><?php echo $title_for_layout; ?></h2>

    <p><?php echo __d(
            'hurad',
            'This can improve the aesthetics, usability, and forward-compatibility of your links.'
        ); ?></p>
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

<div class="form-group">
    <label class="control-label col-lg-2">
        <?php echo __d('hurad', 'Common settings'); ?>
    </label>
   <div class=" col-lg-8">
       <div class="radio">
           <label>
               <?php
               echo $this->Form->input(
                   'common',
                   array(
                       'options' => array(
                           'default' => __d('hurad', 'Default')
                       ),
                       'type' => 'radio',
                       'hiddenField' => false
                   )
               );
               ?>
               <?php echo __d('hurad', '<code>' . $this->Link->getSiteUrl() . '/p/123</code>'); ?>
           </label>
       </div>
       <div class="radio">
           <label>
               <?php
               echo $this->Form->input(
                   'common',
                   array(
                       'options' => array(
                           'day_name' => __d('hurad', 'Day and name')
                       ),
                       'type' => 'radio',
                       'hiddenField' => false
                   )
               );
               ?>
               <?php echo __d(
                   'hurad',
                   '<code>' . $this->Link->getSiteUrl() . '/2012/09/16/sample-post/</code>'
               ); ?>
           </label>
       </div>
       <div class="radio">
           <label>
               <?php
               echo $this->Form->input(
                   'common',
                   array(
                       'options' => array(
                           'month_name' => __d('hurad', 'Month and name')
                       ),
                       'type' => 'radio',
                       'hiddenField' => false
                   )
               );
               ?>
               <?php echo __d('hurad', '<code>' . $this->Link->getSiteUrl() . '/2012/09/sample-post/</code>'); ?>
           </label>
       </div>
   </div>
</div>

<?php echo $this->Form->button(__d('hurad', 'Update'), array('type' => 'submit', 'class' => 'btn btn-primary')); ?>

<?php echo $this->Form->end(); ?>