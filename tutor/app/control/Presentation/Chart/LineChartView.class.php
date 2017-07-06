<?php
/**
 * Chart
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class LineChartView extends TPage
{
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        
        $html = new THtmlRenderer('app/resources/google_line_chart.html');
        $data = array();
        $data[] = [ 'Day', 'Value 1', 'Value 2', 'Value 3' ];
        $data[] = [ 'Day 1',   120,       140,       160 ];
        $data[] = [ 'Day 2',   100,       120,       140 ];
        $data[] = [ 'Day 3',   140,       160,       180 ];
        
        $panel = new TPanelGroup('Line chart');
        $panel->add($html);
        
        // replace the main section variables
        $html->enableSection('main', array('data'   => json_encode($data),
                                           'width'  => '100%',
                                           'height'  => '300px',
                                           'title'  => 'Accesses by day',
                                           'ytitle' => 'Accesses', 
                                           'xtitle' => 'Day'));
        
        // add the template to the page
        $container = new TVBox;
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($panel);
        parent::add($container);
    }
}
