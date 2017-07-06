<?php
/**
 * GeBairros Active Record
 * @author  <your-name-here>
 */
class GeBairros extends TRecord
{
    const TABLENAME = 'ge_bairros';
    const PRIMARYKEY= 'i_bairro';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('usuario');
        parent::addAttribute('dt_sistema');
    }


}
