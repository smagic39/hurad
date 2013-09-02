<?php

App::uses('AppHelper', 'View/Helper');

/**
 * Description of WidgetHelper
 *
 * @author mohammad
 */
class WidgetHelper extends AppHelper
{

    /**
     * Other helpers used by this helper
     *
     * @var array
     * @access public
     */
    public $helpers = array('Form');

    public function sidebar($sidebar_id = null)
    {
        if (is_null($sidebar_id) && !in_array($sidebar_id, Configure::read('sidebars'))) {
            return false;
        }

        $sidebar_widgets = unserialize(Configure::read(Configure::read('template') . '.widgets'));
        $widgets = Configure::read('widgets');
        if (Configure::check(Configure::read('template') . '.widgets') && !is_null(
                Configure::read(Configure::read('template') . '.widgets')
            )
        ) {
            $this->_View->start($sidebar_id);

            foreach ($sidebar_widgets[$sidebar_id] as $widget) {
                echo $this->_View->element(
                    'Widgets/' . $widgets[$widget['widget-id']]['element'],
                    array('data' => HuradWidget::getWidgetData($widget['unique-id']))
                );
            }

            $this->_View->end();
        }

        echo $this->_View->fetch($sidebar_id);
    }

    public function label($for, $text)
    {
        echo $this->Form->label('Widget' . ucfirst($for), $text);
    }

    public function input($inputName, $data)
    {
        if (count($data) > 0) {
            echo $this->Form->input(
                $inputName,
                array(
                    'id' => $inputName,
                    'class' => 'input-block-level',
                    'name' => $inputName,
                    'type' => 'text',
                    'value' => $data[$inputName],
                    'label' => false,
                    'div' => false
                )
            );
        } elseif (isset($inputName)) {
            echo $this->Form->input(
                $inputName,
                array(
                    'id' => $inputName,
                    'class' => 'input-block-level',
                    'name' => $inputName,
                    'type' => 'text',
                    'value' => '',
                    'label' => false,
                    'div' => false
                )
            );
        } else {
            return false;
        }
    }

    public function select($inputName, $data, $options)
    {
        if (count($data) > 0) {
            echo $this->Form->select(
                $inputName,
                $options,
                array(
                    'id' => $inputName,
                    'class' => 'input-block-level',
                    'name' => $inputName,
                    'value' => $data[$inputName],
                    'label' => false,
                    'div' => false,
                    'empty' => false
                )
            );
        } elseif (isset($inputName)) {
            echo $this->Form->select(
                $inputName,
                $options,
                array(
                    'id' => $inputName,
                    'class' => 'input-block-level',
                    'name' => $inputName,
                    'label' => false,
                    'div' => false,
                    'empty' => false
                )
            );
        } else {
            return false;
        }
    }

    public function formExist($element)
    {
        return $this->_View->elementExists('Widgets/' . $element . '-form');
    }

}