<?php
/**
 * Multi Step 2
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class MultiStepRegistration2View extends TPage
{
    private $datagrid;
    
    public function __construct()
    {
        parent::__construct();
        
        // creates one datagrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->setHeight(320);
        
        // define the CSS class
        $this->datagrid->class='tdatagrid_table customized-table';
        
        // import the CSS file
        parent::include_css('app/resources/custom-table.css');

        // add the columns
        $this->datagrid->addQuickColumn('Code',        'code',        'right', 70);
        $this->datagrid->addQuickColumn('Description', 'description', 'left', 550);
        
        $this->datagrid->addQuickAction('Select',   new TDataGridAction(array($this, 'onSelect')),   'code', 'ico_apply.png');
        
        // creates the datagrid model
        $this->datagrid->createModel();
        
        $breadcrumb = new TBreadCrumb;
        $breadcrumb->setHomeController('MultiStepRegistration1View');
        $breadcrumb->addHome();
        $breadcrumb->addItem('Welcome');
        $breadcrumb->addItem('Selection');
        $breadcrumb->addItem('Complete information');
        $breadcrumb->addItem('Confirmation');
        $breadcrumb->select('Selection');
        
        $back = new TElement('a');
        $back->href = (new TAction(array('MultiStepRegistration1View', 'loadPage')))->serialize();
        $back->class = 'btn btn-default';
        $back->generator = 'adianti';
        $back->add('<i class="fa fa-backward blue"/> Back');
        
        $table = new TTable;
        $table->class = 'tform';
        $table->addRow()->addCell( $this->datagrid );
        $row = $table->addRow();
        $row->class = 'tformaction';
        $row->addCell( $back );
        
        $vbox = new TVBox;
        $vbox->add( $breadcrumb );
        $vbox->add( $table );
        
        // wrap the page content
        parent::add($vbox);
    }
    
    /**
     * Load the data into the datagrid
     */
    function onReload()
    {
        $this->datagrid->clear();
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code        = '1';
        $item->description = 'Intro to Computer Science';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code        = '2';
        $item->description = 'Software Development Process';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code        = '3';
        $item->description = 'Software Testing';
        $this->datagrid->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code        = '4';
        $item->description = 'Programming Languages';
        $this->datagrid->addItem($item);
    }
    
    /**
     * method onView()
     * Executed when the user clicks at the view button
     */
    function onSelect($param)
    {
        // get the parameter and shows the message
        $key = $param['key'];
        
        // get the course description
        foreach ($this->datagrid->getItems() as $object)
        {
            if ($key == $object->code)
            {
                $description = $object->description;
            }
        }
        
        TSession::setValue('registration_course', array('course_id' => $key,
                                                        'course_description' => $description) );
        
        TApplication::loadPage('MultiStepRegistration3View');
    }
    
    /**
     * shows the page
     */
    function show()
    {
        $this->onReload();
        parent::show();
    }
}
?>