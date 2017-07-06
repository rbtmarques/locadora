<?php
/**
 * FormInteractionsView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormInteractionsView extends TPage
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
        $this->form = new TQuickForm('form_interaction');
        $this->form->setFormTitle('Dynamic interactions');
        $this->form->class = 'tform';
        
        // create the form fields
        $input_exit   = new TEntry('input_exit');
        $response_a   = new TEntry('response_a');
        $combo_change = new TCombo('combo_change');
        $response_b   = new TCombo('response_b');
        $response_c   = new TEntry('response_c');
        
        $combo_items = array();
        $combo_items['a'] ='Item a';
        $combo_items['b'] ='Item b';
        $combo_items['c'] ='Item c';
        
        $response_a->setEditable(FALSE);
        $response_c->setEditable(FALSE);
        $combo_change->addItems($combo_items);
        $response_b->addItems($combo_items);
        
        // add the fields inside the form
        $this->form->addQuickField('Input with exit action',    $input_exit, 200);
        $this->form->addQuickField('Response A', $response_a, 200);
        $this->form->addQuickField('Combo with change action', $combo_change, 200);
        $this->form->addQuickField('Response B', $response_b, 200);
        $this->form->addQuickField('Response C', $response_c, 200);
        
        $this->form->addQuickAction('View', new TAction(array($this, 'onView')), 'fa:search');
        
        // set exit action for input_exit
        $exit_action = new TAction(array($this, 'onExitAction'));
        $input_exit->setExitAction($exit_action);
        
        // set exit action for input_exit
        $change_action = new TAction(array($this, 'onChangeAction'));
        $combo_change->setChangeAction($change_action);
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);

        parent::add($vbox);
    }
    
    /**
     * Show data
     */
    public function onView()
    {
        // get form data
        $data = $this->form->getData();
        
        // send some data after post
        $obj = new StdClass;
        $obj->combo_change = $data->combo_change; // will fire change action
        $obj->response_b = $data->response_b;     // will update the value after the change action
        
        // send some data to form dynamically
        TForm::sendData('form_interaction', $obj);
        
        // keep the form filled
        $this->form->setData($data);
        
        new TMessage('info', str_replace(',', ',<br> ', json_encode($data)));
    }
    
    /**
     * Action to be executed when the user leaves the input_exit field
     */
    public static function onExitAction($param)
    {
        $obj = new StdClass;
        $obj->response_a = 'Resp. for '.$param['input_exit'].' at ' . date('H:m:s');
        $obj->combo_change = 'a';
        
        TForm::sendData('form_interaction', $obj);
        new TMessage('info', 'Message on field exit. <br>You have typed: ' . $param['input_exit']);
    }
    
    /**
     * Action to be executed when the user changes the combo_change field
     */
    public static function onChangeAction($param)
    {
        $obj = new StdClass;
        $obj->response_c = 'Resp. for opt "'.$param['combo_change'] . '" ' .date('H:m:s');
        TForm::sendData('form_interaction', $obj);
        
        $options = array();
        $options[1] = $param['combo_change'] . ' - one';
        $options[2] = $param['combo_change'] . ' - two';
        $options[3] = $param['combo_change'] . ' - three';
        TCombo::reload('form_interaction', 'response_b', $options);
    }
}
?>