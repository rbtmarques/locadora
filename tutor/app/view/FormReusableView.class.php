<?php
/**
 * FormReusableView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormReusableView extends TQuickForm
{
    public function __construct()
    {
        parent::__construct();
        parent::setFormTitle('Form reusable');
        parent::setProperty( 'class', 'tform' );
        
        $id      = new TEntry('id');
        $name    = new TEntry('name');
        $address = new TEntry('address');
        $phone   = new TEntry('phone');
        $email   = new TEntry('email');
        $gender  = new TCombo('gender');
        $status  = new TCombo('status');
        
        parent::addQuickField( 'Id', $id, '30%' );
        parent::addQuickField( 'Name', $name, '70%' );
        parent::addQuickField( 'Address', $address, '70%' );
        parent::addQuickField( 'Phone', $phone, '70%' );
        parent::addQuickField( 'Email', $email, '70%' );
        parent::addQuickField( 'Gender', $gender, '70%' );
        parent::addQuickField( 'Status', $status, '70%' );
        
        $gender->addItems( [ 'M' => 'Male', 'F' => 'Female' ] );
        $status->addItems( [ 'S' => 'Single', 'C' => 'Committed', 'M' => 'Married' ] );
    }
}
