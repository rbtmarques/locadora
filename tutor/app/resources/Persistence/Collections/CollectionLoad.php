<?php 
class CollectionLoad extends TPage
{ 
    public function __construct() 
    { 
        parent::__construct(); 
        try 
        { 
            TTransaction::open('samples'); 
            $criteria = new TCriteria; 
            $criteria->add(new TFilter('gender', '=', 'F')); 
            
            $repository = new TRepository('Customer'); 
            $customers = $repository->load($criteria); 
            
            foreach ($customers as $customer) 
            { 
                echo $customer->id . ' - ' . $customer->name . '<br>'; 
            } 
            TTransaction::close(); 
        } 
        catch (Exception $e) 
        { 
            new TMessage('error', $e->getMessage()); 
        } 
    } 
} 
?>