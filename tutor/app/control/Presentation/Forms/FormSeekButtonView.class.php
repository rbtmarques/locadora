<?php
/**
 * FormSeekButtonView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormSeekButtonView extends TPage
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
        $this->form = new TQuickForm('form_seek_sample');
        $this->form->setFormTitle('Seek button');
        $this->form->class = 'tform';
        
        // create the form fields
        $city_id1   = new TSeekButton('city_id1');
        $city_name1 = new TEntry('city_name1');
        
        $city_id2   = new TDBSeekButton('city_id2', 'samples', 'form_seek_sample', 'City', 'name', 'city_id2', 'city_name2');
        $city_name2 = new TEntry('city_name2');
        
        $criteria = new TCriteria;
        $criteria->add(new TFilter('id', '>', 1));
        $criteria->add(new TFilter('id', '<', 5));
        $criteria->setProperty('order', 'name');
        
        $city_id3   = new TDBSeekButton('city_id3', 'samples', 'form_seek_sample', 'City', 'name', 'city_id3', 'city_name3', $criteria);
        $city_name3 = new TEntry('city_name3');
        
        // define the action for city_id1
        $obj = new TestCitySeek;
        $action = new TAction(array($obj, 'onReload'));
        $city_id1->setAction($action);
        
        $city_id1->setSize(100);
        $city_id2->setSize(100);
        $city_id3->setSize(100);
        $city_name1->setEditable(FALSE);
        $city_name2->setEditable(FALSE);
        $city_name3->setEditable(FALSE);
        
        $this->form->addQuickFields('Manual SeekButton', array($city_id1, $city_name1));
        $this->form->addQuickFields('Standard SeekButton', array($city_id2, $city_name2));
        $this->form->addQuickFields('Standard with filter', array($city_id3, $city_name3));
        
        $this->form->addQuickAction('Save', new TAction(array($this, 'onSave')), 'fa:save');
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);

        parent::add($vbox);
    }
    
    /**
     * Simulates an save button
     * Show the form content
     */
    public function onSave($param)
    {
        $data = $this->form->getData(); // optional parameter: active record class
        
        // put the data back to the form
        $this->form->setData($data);
        
        // creates a string with the form element's values
        $message = 'City id 1 : ' . $data->city_id1 . '<br>';
        $message.= 'City name 1 : ' . $data->city_name1 . '<br>';
        $message.= 'City id 2 : ' . $data->city_id2 . '<br>';
        $message.= 'City name 2 : ' . $data->city_name2 . '<br>';
        
        // show the message
        new TMessage('info', $message);
    }
}
?>