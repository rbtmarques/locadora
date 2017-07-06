<?php
/**
 * FormCustomView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormCustomView extends TPage
{
    private $form;
    
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new TForm('customform');
        
        // creates the notebook
        $notebook = new BootstrapNotebookWrapper(new TNotebook);
        $this->form->add($notebook);
        
        // creates the containers for each notebook page
        $page1 = new TTable;
        $page2 = new TTable;
        $page1->style = 'padding: 10px';
        $page2->style = 'padding: 10px';
        
        // adds two pages in the notebook
        $notebook->appendPage('Page 1', $page1);
        $notebook->appendPage('Page 2', $page2);
        
        // create the form fields
        $field1      = new TEntry('field1');
        $field2      = new TEntry('field2');
        $field3      = new TDate('field3');
        $field4      = new TDate('field4');
        $field5      = new TColor('field5');
        $field6      = new TCombo('field6');
        $field7      = new TEntry('field7');
        $field8      = new TRadioGroup('field8');
        $field9      = new TCheckGroup('field9');
        $field10     = new TSelect('field10');
        
        $field1->setSize(40);
        $field2->setSize(300);
        $field3->setSize(100);
        $field4->setSize(100);
        $field5->setSize(100);
        $field6->setSize(120);
        $field7->setSize(260);
        $field8->setSize(120);
        $field9->setSize(120);
        $field10->setSize(200, 70);
        
        $items = array( 'a' => 'Item a', 'b' => 'Item b', 'c' => 'Item c' );
        $field6->addItems($items);
        $field8->addItems($items);
        $field9->addItems($items);
        $field10->addItems($items);
        $field8->setLayout('horizontal');
        $field9->setLayout('horizontal');
        
        // add rows for the fields
        $page1->addRowSet( $l1=new TLabel('Field 1'), $field1 );
        $page1->addRowSet( new TLabel('Field 2'), $field2 );
        $page1->addRowSet( new TLabel('Field 3'), array ($field3, new TLabel('Field 4'), $field4) );
        $page1->addRowSet( new TLabel('Field 5'), $field5 );
        $page1->addRowSet( new TLabel('Field 6'), $field6 );
        
        $page2->addRowSet( $l7=new TLabel('Field 7'), $field7 );
        $page2->addRowSet( new TLabel('Field 8'), $field8 );
        $page2->addRowSet( new TLabel('Field 9'), $field9 );
        $page2->addRowSet( new TLabel('Field 10'), $field10 );
        
        $l1->setSize(100);
        $l7->setSize(100);
        
        // creates the action button
        $button1 = new TButton('action1');
        $button1->setAction(new TAction(array($this, 'onSend')), 'Send');
        $button1->setImage('fa:check-circle-o green');
        
        // define wich are the form fields
        $this->form->setFields(array($field1, $field2, $field3, $field4, $field5,
                                     $field6, $field7, $field8, $field9, $field10, $button1));
        
        $panel = new TPanelGroup('Custom Tabbed Form');
        $panel->add($this->form);
        $panel->addFooter($button1);
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($panel);
        
        parent::add($vbox);
    }
    
    /**
     * Get the post data
     */
    public function onSend($param)
    {
        $data = $this->form->getData();
        $this->form->setData($data);
        
        new TMessage('info', str_replace(',', '<br>', json_encode($data)));
    }
}
