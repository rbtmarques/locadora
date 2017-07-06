<?php
/**
 * FormHtmlEditorView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormHtmlEditorView extends TPage
{
    private $form;
    
    /**
     * Page constructor
     */
    function __construct()
    {
        parent::__construct();
        
        // create the form
        $this->form = new TForm;
        
        // creates the table wrapper
        $table = new TTable;
        $this->form->add($table);
        
        // create the form fields
        $html = new THtmlEditor('html_text');
        $html->setSize( '100%', 200);
        
        // create button action
        $button = TButton::create('action1', array($this, 'onShow'), 'Show', 'fa:check-circle-o green');
        
        // pack elements
        $table->addRow()->addCell(new TLabel('HTML Editor', '#6D63B8', 12, 'b'));
        $table->addRow()->addCell( $html );
        $table->addRow()->addCell( $button );
        
        // define wich are the form fields
        $this->form->setFields(array($html, $button));
                
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);
        parent::add($vbox);
    }
    
    /**
     * Show the form content
     */
    public function onShow($param)
    {
        $data = $this->form->getData();
        $this->form->setData($data); // put the data back to the form
        
        // show the message
        new TMessage('info', $data->html_text);
    }
}
