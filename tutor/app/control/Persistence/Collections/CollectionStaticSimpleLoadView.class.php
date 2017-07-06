<?php
/**
 * CollectionStaticSimpleLoadView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class CollectionStaticSimpleLoadView extends TPage
{
    public function __construct()
    {
        // parent classs constructor
        parent::__construct();
        
        // scroll to put the source inside
        $scroll = new TScroll;
        $scroll->setSize(640, 460);
        if (PHP_SAPI !== 'cli') // just under web
        {
            $scroll->style = 'padding: 4px; border-radius: 4px;';
        }
        
        $source = new TSourceCode;
        $source->loadFile('app/resources/Persistence/Collections/CollectionStaticSimpleLoad.php');
        $scroll->add($source);
        
        // wrap the page content
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($scroll);
        parent::add($vbox);
    }
}
