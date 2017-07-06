<?php
/**
 * FormMaskView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormMaskView extends TPage
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
        $this->form = new TQuickForm;
        $this->form->class = 'tform';
        $this->form->setFormTitle('Mask form');
        
        // create the form fields
        $element1 = new TEntry('element1');
        $element2 = new TEntry('element2');
        $element3 = new TEntry('element3');
        $element4 = new TEntry('element4');
        $element5 = new TEntry('element5');
        $element6 = new TEntry('element6');
        $element7 = new TEntry('element7');
        $element8 = new TEntry('element8');
        $element9 = new TEntry('element9');
        $element10= new TEntry('element10');
        
        $element1->setMask('99.999-999');
        $element2->setMask('A!'); // Máscara Alfanumérica Livre
        $element3->setMask('AAA'); // Máscara Alfanumérica Delimitada
        $element4->setMask('S!'); // Máscara Alfabética Livre
        $element5->setMask('SSS'); // Máscara Alfabética Delimitada
        $element6->setMask('9!'); // Máscara Numérica Livre
        $element7->setMask('999'); // Máscara Numérica Delimitada
        $element8->setMask('SSS-9999'); // Máscara Alfabética e Numérica
        $element9->forceUpperCase();
        $element10->forceLowerCase();
        
        // add the fields inside the form
        $this->form->addQuickField('Element 1 (99.999-999)', $element1, 300);
        $this->form->addQuickField('Element 2 (A!) non-delimited alphanumeric', $element2, 300);
        $this->form->addQuickField('Element 3 (AAA) delimited alphanumeric', $element3, 300);
        $this->form->addQuickField('Element 4 (S!) non-delimited alpha', $element4, 300);
        $this->form->addQuickField('Element 5 (SSS) delimited alpha', $element5, 300);
        $this->form->addQuickField('Element 6 (9!) non-delimited numbers', $element6, 300);
        $this->form->addQuickField('Element 7 (999) delimited numbers', $element7, 300);
        $this->form->addQuickField('Element 8 (SSS-999) alpha and numeric', $element8, 300);
        $this->form->addQuickField('Element 9 force uppercase', $element9, 300);
        $this->form->addQuickField('Element 10 force lowercase', $element10, 300);
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);

        parent::add($vbox);
    }
}
