<?php
/**
 * CompleteDataGridView Listing
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class CompleteDataGridView extends TPage
{
    private $form;     // registration form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new TQuickForm('form_search_City');
        $this->form->setFormTitle('Manual datagrid');
        $this->form->class = 'tform';
        
        $name = new TEntry('name');
        $this->form->addQuickField( 'Name:', $name, '70%' );
        $this->form->addQuickAction('Find', new TAction(array($this, 'onSearch')), 'fa:search blue');
        $this->form->addQuickAction('New',  new TAction(array('CompleteFormView', 'onClear')), 'fa:plus-circle green');

        // keep the form filled with the search data
        $name->setValue( TSession::getValue( 'City_name' ) );
        
        // creates a DataGrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->style = 'width: 100%';
        
        // creates the datagrid columns
        $id    = new TDataGridColumn('id', 'Id', 'right', '10%');
        $name  = new TDataGridColumn('name', 'Name', 'left', '60%');
        $state = new TDataGridColumn('state->name', 'State', 'center', '30%');

        // creates the datagrid actions
        $order1 = new TAction(array($this, 'onReload'));
        $order2 = new TAction(array($this, 'onReload'));

        // define the ordering parameters
        $order1->setParameter('order', 'id');
        $order2->setParameter('order', 'name');

        // assign the ordering actions
        $id->setAction($order1);
        $name->setAction($order2);

        // add the columns to the DataGrid
        $this->datagrid->addColumn($id);
        $this->datagrid->addColumn($name);
        $this->datagrid->addColumn($state);

        // creates two datagrid actions
        $action1 = new TDataGridAction(array('CompleteFormView', 'onEdit'));
        $action1->setLabel('Edit');
        $action1->setImage('fa:edit blue');
        $action1->setField('id');
        
        $action2 = new TDataGridAction(array($this, 'onDelete'));
        $action2->setLabel('Delete');
        $action2->setImage('fa:trash red');
        $action2->setField('id');
        
        // add the actions to the datagrid
        $this->datagrid->addAction($action1);
        $this->datagrid->addAction($action2);
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // creates the page structure using a table
        $vbox = new TVBox;
        
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form); // add a row to the form
        $vbox->add($this->datagrid); // add a row to the datagrid
        $vbox->add($this->pageNavigation); // add a row for page navigation
        
        // add the table inside the page
        parent::add($vbox);
    }
    
    /**
     * method onSearch()
     * Register the filter in the session when the user performs a search
     */
    function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        
        // check if the user has filled the form
        if (isset($data->name))
        {
            // creates a filter using what the user has typed
            $filter = new TFilter('name', 'like', "%{$data->name}%");
            
            // stores the filter in the session
            TSession::setValue('City_filter', $filter);
            TSession::setValue('City_name',   $data->name);
            
            // fill the form with data again
            $this->form->setData($data);
        }
        
        $param = array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->onReload($param);
    }
    
    /**
     * method onReload()
     * Load the datagrid with the database objects
     */
    function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'samples'
            TTransaction::open('samples');
            
            // creates a repository for City
            $repository = new TRepository('City');
            $limit = 10;
            
            // creates a criteria
            $criteria = new TCriteria;
            
            // default order
            if (empty($param['order']))
            {
                $param['order'] = 'id';
                $param['direction'] = 'asc';
            }
            
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            
            if (TSession::getValue('City_filter'))
            {
                // add the filter stored in the session to the criteria
                $criteria->add(TSession::getValue('City_filter'));
            }
            
            // load the objects according to criteria
            $objects = $repository->load($criteria);
            
            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    // add the object inside the datagrid
                    $this->datagrid->addItem($object);
                }
            }
            
            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);
            
            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit
            
            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * method onDelete()
     * executed whenever the user clicks at the delete button
     * Ask if the user really wants to delete the record
     */
    function onDelete($param)
    {
        // define the delete action
        $action = new TAction(array($this, 'Delete'));
        $action->setParameters($param); // pass the key parameter ahead
        
        // shows a dialog to the user
        new TQuestion('Do you really want to delete ?', $action);
    }
    
    /**
     * method Delete()
     * Delete a record
     */
    function Delete($param)
    {
        try
        {
            // get the parameter $key
            $key = $param['id'];
            
            TTransaction::open('samples'); // open a transaction with database 'samples'
            $object = new City($key);      // instantiates object City
            $object->delete();             // deletes the object from the database
            TTransaction::close();         // close the transaction
            
            // reload the listing
            $this->onReload( $param );
            
            // shows the success message
            new TMessage('info', "Record Deleted");
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * method show()
     * Shows the page
     */
    function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded)
        {
            $this->onReload( func_get_arg(0) );
        }
        parent::show();
    }
}
