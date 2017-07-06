<?php
/**
 * MultiStepMultiFormView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class MultiStepMultiFormView extends TPage
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new TQuickForm('form_account');
        $this->form->class = 'tform';
        $this->form->setFormTitle('Create account');
        
        // create the form fields
        $email       = new TEntry('email');
        $password    = new TPassword('password');
        $confirm     = new TPassword('confirm');

        $this->form->addQuickField('Email: ', $email,  '70%');
        $this->form->addQuickField('Password: ', $password,  '70%');
        $this->form->addQuickField('Confirm password: ', $confirm,  '70%');

        // validations
        $email->addValidation('Email', new TRequiredValidator);
        $email->addValidation('Email', new TEmailValidator);
        $password->addValidation('Password', new TRequiredValidator);
        $confirm->addValidation('Confirm password', new TRequiredValidator);

        // add a form action
        $this->form->addQuickAction('Next', new TAction(array($this, 'onNextForm')), 'fa:chevron-circle-right green');
        
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);
        
        // add the form to the page
        parent::add($vbox);
    }
    
    /**
     * Load form from session
     */
    public function onLoadFromSession()
    {
        $data = TSession::getValue('form_step1_data');
        $this->form->setData($data);
    }
    
    /**
     * onNextForm
     */
    public function onNextForm()
    {
        try
        {
            $this->form->validate();
            $data = $this->form->getData();
            
            if ($data->password !== $data->confirm)
            {
                throw new Exception('Passwords do not match');
            }
            // store data in the session
            TSession::setValue('form_step1_data', $data);
            
            // Load another page
            AdiantiCoreApplication::loadPage('MultiStepMultiForm2View', 'onLoadFromForm1', (array) $data);
            
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}
