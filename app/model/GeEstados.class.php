<?php
/**
 * GeEstados Active Record
 * @author  <your-name-here>
 */
class GeEstados extends TRecord
{
    const TABLENAME = 'ge_estados';
    const PRIMARYKEY= 'i_estado';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('sigla');
        parent::addAttribute('pais');
        parent::addAttribute('usuario');
        parent::addAttribute('dt_sistema');
    }


}
