<?php
namespace Modules\Forms;


use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Submit;
 
class SummariesForm extends Form
{
    public function initialize($entity = null, $options = null)
    {
        $this->add(
            new Text(
                'summaryDate',
                [
                    'maxlength'   => 10,
                    'placeholder' => '集計日付を入力',
                ]
            )
        );

        $this->add(
            new Submit(
                'Search',
                [
                    'class' => 'btn btn-success'
                ]
            )
        );
    }
}