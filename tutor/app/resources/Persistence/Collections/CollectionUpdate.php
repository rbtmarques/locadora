<?php 
class CollectionUpdate extends TPage
{ 
    public function __construct() 
    { 
        parent::__construct(); 
        try 
        { 
            TTransaction::open('samples'); 
            $criteria = new TCriteria; 
            $criteria->add(new TFilter('city_id', '=', '4')); 
            
            $repository = new TRepository('Customer'); 
            $customers = $repository->load($criteria); 
            
            foreach ($customers as $customer) 
            { 
                $customer->phone = '84 '.substr($customer->phone, 3); 
                $customer->store(); 
            } 
            new TMessage('info', 'Records updated'); 
            TTransaction::close(); 
        } 
        catch (Exception $e) 
        { 
            new TMessage('error', $e->getMessage()); 
        } 
    } 
} 
?>