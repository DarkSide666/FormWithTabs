<?php
class Model_Contract extends SQLModel
{
    public $table = 'contract';

    function init()
    {
        parent::init();
        
        $this->hasOne('Client');
        
        $this->addField('start_date')->type('date');
        $this->addField('end_date')->type('date');
    }
}
