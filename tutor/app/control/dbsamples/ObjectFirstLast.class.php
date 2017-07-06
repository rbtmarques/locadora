<?php 
class ObjectFirstLast extends TPage
{ 
    public function __construct() 
    { 
        parent::__construct(); 
        try 
        { 
            TTransaction::open('samples'); // open transaction
            
            $customer= new Customer;
            
            echo 'The first ID : ' . $customer->getFirstID() . "<br>\n";
            echo 'The last  ID : ' . $customer->getLastID() . "<br>\n"; 
            
            TTransaction::close(); // closes transaction
        } 
        catch (Exception $e) 
        { 
            new TMessage('error', $e->getMessage()); 
        } 
    } 
} 
?>