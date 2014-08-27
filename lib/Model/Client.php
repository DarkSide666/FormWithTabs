<?php
class Model_Client extends SQLModel
{
    public $table = 'client';

    function init()
    {
        parent::init();
        
        $this->addField('name');
        $this->addField('email');
        
        $this->hasMany('Contract');
        $this->hasMany('Invoice');
    }
}
