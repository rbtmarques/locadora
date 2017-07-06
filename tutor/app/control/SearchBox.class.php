<?php
/**
 * Search Box
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SearchBox extends TPage
{
    private $form;
    
    /**
     * Constructor method
     */
    public function __construct()
    {
        parent::__construct('search_box');
        $this->form = new TForm('search_box');
        
        $input = new TMultiSearch('input');
        $input->setSize(240,28);
        $input->addItems( $this->getPrograms() );
        $input->setMinLength(1);
        $input->setMaxSize(1);
        $input->setChangeAction(new TAction(array('SearchBox', 'loadProgram')));
        
        $this->form->add($input);
        $this->form->setFields(array($input));
        parent::add($this->form);
    }
    
    /**
     * Returns an indexed array with all programs
     */
    public function getPrograms()
    {
        $xml = simplexml_load_file('menu.xml');
        foreach ($xml as $xmlElement)
        {
            $atts   = $xmlElement->attributes();
            $index  = (string) $atts['label'];
            
            if ($xmlElement->menu)
            {
                foreach ($xmlElement->menu->menuitem as $subXmlElement)
                {
                    $subindex = (string) $subXmlElement['label'];
                    $subatts   = $subXmlElement->attributes();
                    if ($subXmlElement->menu)
                    {
                        foreach ($subXmlElement->menu->menuitem as $option)
                        {
                            $optatts   = $option->attributes();
                            $label  = (string) $option['label'];
                            $action = (string) $option-> action;
                            $icon   = (string) $option-> icon;
                            $items[ $action ] = $label;
                        }
                    }
                }
            }
        }
        return $items;
    }
    
    /**
     * Load an specific program
     */
    public static function loadProgram($param)
    {
        $program = $param['input'][0];
        if ($program)
        {
            TApplication::loadPage($program);
        }
    }
}
