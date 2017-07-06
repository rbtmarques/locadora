<?php
/**
 * StandardFormView Registration
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class StandardFormView extends TStandardForm
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        parent::setDatabase('samples');    // defines the database
        parent::setActiveRecord('City');   // defines the active record
        
        // creates the form
        $this->form = new TQuickForm('form_City');
        $this->form->class = 'tform'; // CSS class
        $this->form->style = 'width: 100%';
        
        // define the form title
        $this->form->setFormTitle('Standard Form');
        
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
        $this->form->addQuickAction('Listing',  new TAction(array('StandardDataGridView', 'onReload')), 'fa:table blue');

        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);

        parent::add($vbox);
    }
}
