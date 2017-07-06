<?php
/**
 * FullCalendarStaticView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FullCalendarStaticView extends TPage
{
    private $fc;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->fc = new TFullCalendar(date('Y-m-d'), 'month');
        $this->fc->setTimeRange( '06:00:00', '20:00:00' );
        
        $today = date("Y-m-d");// current date
        $before_yesterday = (new DateTime(date('Y-m-d')))->sub(new DateInterval("P2D"))->format('Y-m-d');
        $yesterday        = (new DateTime(date('Y-m-d')))->sub(new DateInterval("P1D"))->format('Y-m-d');
        $tomorrow         = (new DateTime(date('Y-m-d')))->add(new DateInterval("P1D"))->format('Y-m-d');
        $after_tomorrow   = (new DateTime(date('Y-m-d')))->add(new DateInterval("P2D"))->format('Y-m-d');
        
        $this->fc->addEvent(1, 'Event 1', $before_yesterday.'T08:30:00', $before_yesterday.'T12:30:00', null, '#C04747');
        $this->fc->addEvent(2, 'Event 2', $before_yesterday.'T14:30:00', $before_yesterday.'T18:30:00', null, '#668BC6');
        $this->fc->addEvent(3, 'Event 3', $yesterday.'T08:30:00', $yesterday.'T12:30:00', null, '#C04747');
        $this->fc->addEvent(4, 'Event 4', $yesterday.'T14:30:00', $yesterday.'T18:30:00', null, '#668BC6');
        $this->fc->addEvent(5, 'Event 5', $today.'T08:30:00', $today.'T12:30:00', null, '#FF0000');
        $this->fc->addEvent(6, 'Event 6', $today.'T14:30:00', $today.'T18:30:00', null, '#5AB34B');
        $this->fc->addEvent(7, 'Event 7', $tomorrow.'T08:30:00', $tomorrow.'T12:30:00', null, '#FF0000');
        $this->fc->addEvent(8, 'Event 8', $tomorrow.'T14:30:00', $tomorrow.'T18:30:00', null, '#5AB34B');
        $this->fc->addEvent(9, 'Event 9', $after_tomorrow.'T08:30:00', $after_tomorrow.'T12:30:00', null, '#FF0000');
        $this->fc->addEvent(10, 'Event 10', $after_tomorrow.'T14:30:00', $after_tomorrow.'T18:30:00', null, '#FF8C05');
        
        $this->fc->setDayClickAction(new TAction(array($this, 'onDayClick')));
        $this->fc->setEventClickAction(new TAction(array($this, 'onEventClick')));
        parent::add( $this->fc );
    }
    
    public static function onDayClick($param)
    {
        $date = $param['date'];
        new TMessage('info', "You clicked at date: {$date}");
    }
    
    public static function onEventClick($param)
    {
        $id = $param['id'];
        new TMessage('info', "You clicked at id: {$id}");
    }
}
