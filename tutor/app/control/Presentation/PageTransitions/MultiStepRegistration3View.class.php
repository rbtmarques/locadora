<?php
/**
 * Multi Step 3
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class MultiStepRegistration3View extends TPage
{
    protected $form; // form
    protected $notebook;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new TQuickForm('form_account');
        $this->form->setFormTitle('Personal details');
        $this->form->class = 'tform';
        
        // create the form fields
        $email        = new TEntry('email');
        $first_name   = new TEntry('first_name');
        $last_name    = new TEntry('last_name');
        $phone        = new TEntry('phone');
        
        // add the fields
        $this->form->addQuickField('Email: ', $email,  200);
        $this->form->addQuickField('First name: ', $first_name,  200);
        $this->form->addQuickField('Last name: ', $last_name,  200);
        $this->form->addQuickField('Phone: ', $phone,  200);
        
        // validations
        $email->addValidation('Email', new TEmailValidator);
        $first_name->addValidation('First name', new TRequiredValidator);
        $last_name->addValidation('Last name', new TRequiredValidator);
        $phone->addValidation('Phone', new TRequiredValidator);

        // add a form action
        $this->form->addQuickAction('Confirm', new TAction(array($this, 'onConfirm')), 'ico_apply.png');
        $this->form->addQuickAction('Back', new TAction(array($this, 'onBackForm')), 'ico_back.png');
        
        $breadcrumb = new TBreadCrumb;
        $breadcrumb->setHomeController('MultiStepRegistration1View');
        $breadcrumb->addHome();
        $breadcrumb->addItem('Welcome');
        $breadcrumb->addItem('Selection');
        $breadcrumb->addItem('Complete information');
        $breadcrumb->addItem('Confirmation');
        $breadcrumb->select('Complete information');
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->add( $breadcrumb );
        $vbox->add( $this->form );
        parent::add($vbox);
    }
    
    /**
     * Load the previous form
     */
    public function onBackForm()
    {
        // Load another page
        TApplication::loadPage('MultiStepRegistration2View');
    }
    
    /**
     * confirmation screen
     */
    public function onConfirm()
    {
        try
        {
            $this->form->validate();
            $data = $this->form->getData();
            TSession::setValue('registration_data', (array) $data);
            
            TApplication::loadPage('MultiStepRegistration4View');
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}
?>