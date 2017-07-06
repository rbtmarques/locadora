<?php
/**
 * StandardDataGridView Listing
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class StandardDataGridView extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setDatabase('samples');        // defines the database
        parent::setActiveRecord('City');       // defines the active record
        parent::addFilterField('name', 'like', 'name'); // filter field, operator, form field
        parent::setDefaultOrder('id', 'asc');  // define the default order
        
        // creates the form
        $this->form = new TQuickForm('form_search_City');
        $this->form->setFormTitle('Standard datagrid');
        $this->form->class = 'tform';
        
        $name = new TEntry('name');
        $this->form->addQuickField( 'Name:', $name, '70%' );
        $this->form->addQuickAction('Find', new TAction(array($this, 'onSearch')), 'fa:search blue');
        $this->form->addQuickAction('New',  new TAction(array('StandardFormView', 'onClear')), 'fa:plus-circle green');
        
        // keep the form filled with the search data
        $this->form->setData( TSession::getValue('City_filter_data') );
        
        // creates the DataGrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->style = "width: 100%";
        
        // creates the datagrid columns
        $this->datagrid->addQuickColumn('Id', 'id', 'right', '10%', new TAction(array($this, 'onReload')), array('order', 'id'));
        $this->datagrid->addQuickColumn('Name', 'name', 'left', '60%', new TAction(array($this, 'onReload')), array('order', 'name'));
        $this->datagrid->addQuickColumn('State', 'state->name', 'center', '30%');

        // creates two datagrid actions
        $this->datagrid->addQuickAction('Edit', new TDataGridAction(array('StandardFormView', 'onEdit')), 'id', 'fa:edit blue');
        $this->datagrid->addQuickAction('Delete', new TDataGridAction(array($this, 'onDelete')), 'id', 'fa:trash red');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // creates the page structure using a table
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);
        $vbox->add($this->datagrid);
        $vbox->add($this->pageNavigation);
        
        // add the table inside the page
        parent::add($vbox);
    }
}
