<?php
class FormWithTabs extends Form {

    /**
     * VirtualPage object will be used to display content. That is to ensure
     * that none of your other content you put AROUND the FormWithTabs would
     * mess with its content.
     *
     * You can add more stuff on this page, by calling
     * virtual_page->getPage()->add('Hello!');
     */
    public $virtual_page = null;

    /**
     * After Form is initialized, this will point to a tabs object IF
     * Form has at least one tab.
     * You can enhance the tabs all you want
     */
    public $tabs = null;

    /**
     * By default, Form will simply use "Tabs" class for tabs, but if you
     * would like to use your custom tabs, specify it inside associative
     * array as second argument to add()
     *
     * $this->add('NewForm', array('tabs_class'=>'MyTabs'));
     */
    public $tabs_class = 'Tabs';

    /**
     * You can pass additional options for tabs using this array
     *
     * $this->add('NewForm', array('tabs_options'=>array('position'=>'left')));
     */
    public $tabs_options = array();

    /**
     * title of first tab
     */
    public $title = 'General';

    /**
     * Classname of sub-view in refTab
     */
    public $subview_class = 'Grid';

    /**
     * This is set to ID of the model.
     * In theory this can also be 0, so use is_null()
     */
    protected $id = null;

    /**
     * GET parameter name of virtual page
     */
    protected $name_id;



    /**
     * Initialization
     *
     * @return void
     */
    function init()
    {
        parent::init();
        
        // add Virtual Page into parent View
        $this->virtual_page = $this->owner->add('VirtualPage');
        $this->name_id = $this->virtual_page->name . '_id';

        // sticky virtual page parameters, extract record ID
        if (isset($_GET[$this->name_id])) {
            $this->api->stickyGET($this->name_id);
            $this->id = $_GET[$this->name_id];
        }

        // add Tabs in owners View and move this form in first tab
        $this->tabs = $this->virtual_page->getPage()->add('Tabs');
        $this->tabs
            ->addTab($this->title)
            ->add($this);
    }
    
    /* Add tab which dynamically load sub-view (like grid or CRUD) with forms model related records */
    function addRefTab($ref, $title = null)
    {
        // model has to be set before adding refTabs
        if (! $this->model) {
            throw $this->exception('Must set '.get_class($this).' model first');
        }

        // add refTab only if Form has loaded record, so refTabs will show
        // only when editing existing record, not creating new one
        if (! $this->model->loaded()) {
            return $this->tabs;
        }

        // create URL
        $page = $this->api->url(
                    $this->virtual_page->getURL($ref)
                    ,array(
                        $this->name_id  => $this->model->id,
                    )
                );

        // add Tab
        $t = $this->tabs->addTabURL($page, $title ?: $ref);

        // current ref tab is active then create sub-Grid
        $active_ref = $this->virtual_page->isActive();
        if ($active_ref && $active_ref == $ref) {

            // load model, create grid with referenced records
            $m = $this->model->load($this->id);
            $sub = $this->virtual_page->getPage()->add($this->subview_class);
            $sub->setModel($m->ref($active_ref));

            // render only grid object
            $this->api->cut($sub);
        }

        return $t;
    }

    /* Proxy method: add tab and returns it so that you can add static content */
    function addTab($title, $name = null)
    {
        return $this->tabs->addTab($title, $name);
    }

}
