<?php
class CollectionStaticSimpleLoad extends TPage
{
    public function __construct()
    {
        parent::__construct();
        try
        {
            TTransaction::open('samples');
            
            // load customers
            $customers = Customer::where('gender', '=', 'M')
                                 ->where('name', 'like', 'A%')
                                 ->load();
                                 
            foreach ($customers as $customer)
            {
                echo $customer->id . ' - ' . $customer->name . '<br>';
            }
            TTransaction::close(); // fecha a transação.
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}
