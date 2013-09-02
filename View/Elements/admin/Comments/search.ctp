<?php

$query = '';
if (isset($this->params['named']['q'])) {
    $query = $this->params['named']['q'];
}

echo $this->Form->create(
    'Comment',
    array(
        'url' => array('admin' => true, 'action' => 'index'),
        'class' => 'form-search pull-right',
        'inputDefaults' => array(
            'label' => false,
            'div' => false,
        ),
        'id' => 'AdminSearchForm'
    )
);
echo $this->Html->div('input-append');
echo $this->Form->input('Comment.q', array('value' => $query, 'class' => 'span9 search-query'));
echo $this->Form->button(__('Search'), array('type' => 'submit', 'class' => 'btn', 'div' => false));
echo '</div>';
echo $this->Form->end();
?>