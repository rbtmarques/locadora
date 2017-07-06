<?php
/**
 * FormBuilderView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormBuilderView extends TPage
{
    private $form;
    
    /**
     * Class constructor
     * Creates the page
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->form = new BootstrapFormBuilder;
        $this->form->setFormTitle('Bootstrap Form Builder');
        
        $label1 = new TLabel('Some label', '#7D78B6', 12, 'bi');
        $label1->style='text-align:left;border-bottom:1px solid #c0c0c0;width:100%';
        
        $this->form->appendPage('Page 1');
        $this->form->addContent( [$label1] );
        
        $field1a = new TEntry('row1a');
        $field2a = new TDate('row2a');
        $field2b = new TCombo('row2b');
        $field3a = new TEntry('row3a');
        $field3b = new TEntry('row3b');
        $field3c = new TEntry('row3c');
        $field3d = new TEntry('row3d');
        $field4a = new TText('row4a');
        
        // add a row with 2 slots
        $this->form->addFields( [ new TLabel('Row 1') ],
                                [ $field1a ] );
        
        // add a row with 2 slots
        $this->form->addFields( [ new TLabel('Row 2') ],
                                [ $field2a, $field2b ] );
        
        // add a row with 4 slots
        $this->form->addFields( [ new TLabel('Row 3') ],
                                [ $field3a, $field3b ],
                                [ new TLabel('Label') ],
                                [ $field3c, $field3d ] );
        
        $field2b->addItems( ['1' => 'One', '2' => 'Two'] );
        
        $field1a->setSize('70%');
        $field2a->setSize('120');
        $field2b->setSize('75%');
        
        $field3a->setSize('50%');
        $field3b->setSize('50%');
        $field3c->setSize('50%');
        $field3d->setSize('50%');
        
        $this->form->appendPage('Page 2');
        
        $label2 = new TLabel('Another label', '#7D78B6', 12, 'bi');
        $label2->style='text-align:left;border-bottom:1px solid #c0c0c0;width:100%';
        
        $this->form->addContent( [$label2] );
        $this->form->addFields( [new TLabel('Row 4')], [$field4a ]);
        $field4a->setSize('100%', 100);
        
        $this->form->addAction('Send', new TAction(array($this, 'onSend')), 'fa:check-circle-o green');
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);

        parent::add($this->form);
    }
    
    /**
     * Post data
     */
    public function onSend($param)
    {
        $data = $this->form->getData();
        $this->form->setData($data);
        
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}
