<?php
/**
 * FormClientInteractionsView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormClientInteractionsView extends TPage
{
    private $form;
    
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        
        // create the form using TQuickForm class
        $this->form = new TQuickForm('form_bmi');
        $this->form->class = 'tform';
        $this->form->setFormTitle('Form client interaction');
        
        // create the form fields
        $mass   = new TEntry('mass');
        $height = new TEntry('height');
        $result = new TEntry('result');
        
        $result->setEditable(FALSE);
        // add the fields inside the form
        $this->form->addQuickField('Mass (Kg)',  $mass,    100);
        $this->form->addQuickField('Height (m)', $height, 100);
        $this->form->addQuickField('Result', $result, 100);
        
        $mass->onBlur   = 'calculate_bmi()';
        $height->onBlur = 'calculate_bmi()';
        
        TScript::create('calculate_bmi = function() {
            if (document.form_bmi.mass.value > 0 && document.form_bmi.height.value > 0)
            {
                form_bmi.result.value = parseFloat(form_bmi.mass.value) /
                               Math.pow(parseFloat(form_bmi.height.value),2);
            }
        };');
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);

        parent::add($vbox);
    }
}
