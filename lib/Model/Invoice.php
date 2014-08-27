<?php
class Model_Invoice extends SQLModel
{
    public $table = 'invoice';

    function init()
    {
        parent::init();

        $this->hasOne('Client');
        
        $this->addField('inv_date')->type('date');
        $this->addField('sum_invoice')->type('money');
        
    }
}
