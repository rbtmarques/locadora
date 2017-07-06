<?php
/**
 * CustomerFormView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class CustomerFormView extends TPage
{
    private $form; // form
    private $table_contacts;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_customer');
        $this->form->setFormTitle('Customer');
        
        // create the form fields
        $code           = new TEntry('id');
        $name           = new TEntry('name');
        $address        = new TEntry('address');
        $phone          = new TEntry('phone');
        $city_id        = new TSeekButton('city_id');
        $city_name      = new TEntry('city_name');
        $birthdate      = new TDate('birthdate');
        $email          = new TEntry('email');
        $gender         = new TRadioGroup('gender');
        $status         = new TCombo('status');
        $category_id    = new TDBCombo('category_id', 'samples', 'Category', 'id', 'name');
        
        $city_id->setAction(new TAction(array('CitySeek', 'onReload')));
        
        // add the combo options
        $gender->addItems( [ 'M' => 'Male', 'F' => 'Female' ] );
        $status->addItems( [ 'S' => 'Single', 'C' => 'Committed', 'M' => 'Married' ] );
        $gender->setLayout('horizontal');
        
        // define some properties for the form fields
        $code->setEditable(FALSE);
        $code->setSize(100);
        $city_id->setSize(120);
        $city_name->setSize(150);
        $city_name->setEditable(FALSE);
        $name->setSize(320);
        $address->setSize(320);
        $phone->setSize(150);
        $email->setSize(150);
        $birthdate->setSize(150);
        $status->setSize(150);
        $category_id->setSize(150);
        
        $this->form->appendPage('Basic data');
        $this->form->addFields( [ new TLabel('Code') ],     [ $code ] );
        $this->form->addFields( [ new TLabel('Name') ],     [ $name ] );
        $this->form->addFields( [ new TLabel('Address') ],  [ $address ] );
        $this->form->addFields( [ new TLabel('City') ],     [ $city_id ], [ new TLabel('Name') ], [ $city_name ] );
        $this->form->addFields( [ new TLabel('Phone') ],    [ $phone ], [ new TLabel('BirthDate') ], [ $birthdate ] );
        $this->form->addFields( [ new TLabel('Status') ],   [ $status ], [ new TLabel('Email') ], [ $email ]);
        $this->form->addFields( [ new TLabel('Category') ], [ $category_id ], [ new TLabel('Gender') ], [ $gender ] );
        
        $skill_list = new TDBCheckGroup('skill_list', 'samples', 'Skill', 'id', 'name');
        
        $this->form->appendPage('Contacts');
        $this->table_contacts = new TTable;
        $this->table_contacts->width = '100%';
        $this->table_contacts->addSection('thead');
        $this->table_contacts->addRowSet( new TLabel('Type', '#8082C3',10, 'b'), new TLabel('Value', '#8082C3',10, 'b'));
        $this->form->addContent( [ new TLabel('Contacts') ],     [ $this->table_contacts ] );
        
        $this->form->appendPage('Skills');
        $this->form->addFields( [ new TLabel('Skill') ],     [ $skill_list ] );
        
        $this->form->addAction( 'Save', new TAction(array($this, 'onSave')), 'fa:save green' );
        $this->form->addAction( 'Clear', new TAction(array($this, 'onClear')), 'fa:eraser red' );
        $this->form->addAction( 'List', new TAction(array('CustomerDataGridView', 'onReload')), 'fa:table blue' );
        
        // wrap the page content
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', 'CustomerDataGridView'));
        $vbox->add($this->form);
        
        // add the form inside the page
        parent::add($vbox);
    }
    
    /**
     * method onSave
     * Executed whenever the user clicks at the save button
     */
    public static function onSave($param)
    {
        try
        {
            // open a transaction with database 'samples'
            TTransaction::open('samples');
            
            if (empty($param['birthdate']))
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', 'Birthdate'));
            }
            
            // read the form data and instantiates an Active Record
            $customer = new Customer;
            $customer->fromArray( $param );
            
            if( !empty($param['contact_type']) AND is_array($param['contact_type']) )
            {
                foreach( $param['contact_type'] as $row => $contact_type)
                {
                    if ($contact_type)
                    {
                        $contact = new Contact;
                        $contact->type  = $contact_type;
                        $contact->value = $param['contact_value'][$row];
                        
                        // add the contact to the customer
                        $customer->addContact($contact);
                    }
                }
            }
            
            if ( !empty($param['skill_list']) )
            {
                foreach ($param['skill_list'] as $skill_id)
                {
                    // add the skill to the customer
                    $customer->addSkill(new Skill($skill_id));
                }
            }
            
            // stores the object in the database
            $customer->store();
            
            $data = new stdClass;
            $data->id = $customer->id;
            TForm::sendData('form_customer', $data);
            
            // shows the success message
            new TMessage('info', 'Record saved');
            
            TTransaction::close(); // close the transaction
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
     * method onEdit
     * Edit a record data
     */
    function onEdit($param)
    {
        try
        {
            if (isset($param['id']))
            {
                // open a transaction with database 'samples'
                TTransaction::open('samples');
                
                // load the Active Record according to its ID
                $customer = new Customer($param['id']);
                
                // load the contacts (composition)
                $contacts = $customer->getContacts();
                
                if ($contacts)
                {
                    $this->table_contacts->addSection('tbody');
                    foreach ($contacts as $contact)
                    {
                        $this->addContactRow($contact);
                    }
                    
                    // create add button
                    $add = new TButton('clone');
                    $add->setLabel('Add');
                    $add->setImage('fa:plus-circle green');
                    $add->addFunction('ttable_clone_previous_row(this)');
                            
                    // add buttons in table
                    $this->table_contacts->addRowSet([$add]);
                }
                else
                {
                    $this->onClear($param);
                }
                
                // load the skills (aggregation)
                $skills = $customer->getSkills();
                $skill_list = array();
                if ($skills)
                {
                    foreach ($skills as $skill)
                    {
                        $skill_list[] = $skill->id;
                    }
                }
                $customer->skill_list = $skill_list;
                
                // fill the form with the active record data
                $this->form->setData($customer);
                
                // close the transaction
                TTransaction::close();
            }
            else
            {
                $this->onClear($param);
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
    
    /**
     * Add contact row
     */
    public function addContactRow($item)
    {
        $uniqid = mt_rand(1000000, 9999999);
        
        $contact_type = new TEntry('contact_type[]');
        $contact_type->{'data-row'} = $this->detail_row;
        $contact_type->setId('contact_type_'.$uniqid);
        $contact_type->setSize('100%');
        
        if (!empty($item->type))
        {
            $contact_type->setValue($item->type);
        }
        
        $contact_value = new TEntry('contact_value[]');
        $contact_value->{'data-row'} = $this->detail_row;
        $contact_value->setId('contact_value_'.$uniqid);
        $contact_value->setSize('100%');
        
        if (!empty($item->value))
        {
            $contact_value->setValue($item->value);
        }
        
        // create delete button
        $del = new TImage('fa:trash-o red');
        $del->onclick = 'ttable_remove_row(this)';
        $row = $this->table_contacts->addRowSet( $contact_type, $contact_value, $del );
        $row->{'data-row'} = $this->detail_row;
        
        $this->form->addField($contact_type);
        $this->form->addField($contact_value);
        
        $this->detail_row ++;
    }
    
    /**
     * Clear form
     */
    public function onClear($param)
    {
        $this->form->clear();
        
        $this->table_contacts->addSection('tbody');
        $this->addContactRow( new stdClass );
        
        // create add button
        $add = new TButton('clone');
        $add->setLabel('Add');
        $add->setImage('fa:plus-circle green');
        $add->addFunction('ttable_clone_previous_row(this)');
        
        // add buttons in table
        $this->table_contacts->addRowSet([$add]);
    }
}
