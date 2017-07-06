<?php
/**
 * FormConditionalView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormConditionalView extends TPage
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
        $this->form = new TQuickForm('cond_form');
        $this->form->setFormTitle('Conditional form');
        $this->form->class = 'tform';
        
        // create the form fields
        $animal = new TEntry('animal');
        $color  = new TEntry('color');
        
        $animal->setExitAction(new TAction(array($this, 'onEnableAction')));
        $color->setExitAction(new TAction(array($this, 'onEnableAction')));
        
        // add the fields inside the form
        $this->form->addQuickField('Animal (type "elephant"): ', $animal, 200);
        $this->form->addQuickField('Color (type "blue")',  $color, 200);
        
        // define the form action 
        $this->form->addQuickAction('Save', new TAction(array($this, 'onSave')), 'ico_save.png');
        
        self::onEnableAction(array());
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);
        
        parent::add($vbox);
    }
    
    /**
     * Executed when user leaves the fields
     */
    public static function onEnableAction($data)
    {
        if ( isset($data['animal']) AND isset($data['color']))
        {
            if ( ($data['animal'] == 'elephant') AND ($data['color'] == 'blue') )
            {
                TButton::enableField('cond_form', 'save');
            }
            else
            {
                TButton::disableField('cond_form', 'save');
            }
        }
        else
        {
            TButton::disableField('cond_form', 'save');
        }
    }
    
    /**
     * Simulates an save button
     * Show the form content
     */
    public function onSave($param)
    {
        $data = $this->form->getData();
        $this->form->setData($data);
        
        $message = '';
        $message.= 'Animal : ' . $data->animal . '<br>';
        $message.= 'Color : '  . $data->color . '<br>';
        new TMessage('info', $message);
        
        self::onEnableAction((array) $data);
    }
}
?>