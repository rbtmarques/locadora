<?php
/**
 * CompleteFormView Registration
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class CompleteFormView extends TPage
{
    private $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new TQuickForm('form_City');
        $this->form->class = 'tform'; // CSS class
        $this->form->style = 'width: 100%';
        
        // define the form title
        $this->form->setFormTitle('Manual Form');
        
        // create the form fields
        $id       = new TEntry('id');
        $name     = new TEntry('name');
        $state_id = new TDBCombo('state_id', 'samples', 'State', 'id', 'name');
        $id->setEditable(FALSE);
        
        // add the form fields
        $this->form->addQuickField('ID', $id,  '30%');
        $this->form->addQuickField('Name', $name,  '70%', new TRequiredValidator);
        $this->form->addQuickField('State', $state_id,  '70%', new TRequiredValidator);
        
        // define the form action
        $this->form->addQuickAction('Save', new TAction(array($this, 'onSave')), 'fa:save green');
        $this->form->addQuickAction('Clear',  new TAction(array($this, 'onClear')), 'fa:eraser red');
        $this->form->addQuickAction('Listing',  new TAction(array('CompleteDataGridView', 'onReload')), 'fa:table blue');
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);

        parent::add($vbox);
    }

    /**
     * method onSave()
     * Executed whenever the user clicks at the save button
     */
    function onSave()
    {
        try
        {
            // open a transaction with database 'samples'
            TTransaction::open('samples');
            
            $this->form->validate(); // run form validation
            $object = $this->form->getData('City');    // get the form data into an City Active Record 
            $object->store();                          // stores the object
            
            // fill the form with the active record data
            $this->form->setData($object);
            
            TTransaction::close();  // close the transaction
            
            // shows the success message
            new TMessage('info', 'Record saved');
            // reload the listing
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
     * Clear form
     */
    public function onClear()
    {
        $this->form->clear();
    }
    
    /**
     * method onEdit()
     * Executed whenever the user clicks at the edit button da datagrid
     */
    function onEdit($param)
    {
        try
        {
            if (isset($param['id']))
            {
                // get the parameter $key
                $key = $param['id'];
                
                TTransaction::open('samples');   // open a transaction with database 'samples'
                $object = new City($key);        // instantiates object City
                $this->form->setData($object);   // fill the form with the active record data
                TTransaction::close();           // close the transaction
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
}
