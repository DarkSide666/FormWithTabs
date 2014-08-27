<?php
class page_test extends Page_Basic
{
    function init()
    {
        parent::init();
        $this->setTitle('FormWithTabs test');

        // form and model
        $f = $this->add('FormWithTabs');
        $m = $this->add('Model_Client')->load(666);
        $f->setModel($m);
        
        // ref tabs
        $f->addRefTab('Invoice');   // 1:n client invoices
        $f->addRefTab('Contract');  // 1:n client contract
    }
}
