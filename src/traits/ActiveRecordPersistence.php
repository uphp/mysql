<?php
namespace uphp\model\traits;

trait ActiveRecordPersistence
{
    /**
     * Guarda o nome da table, por default é o nome da class escrita em minusculo e no plural
     * Para definir uma outro nome de tabela basta definir "protected $table = 'tabela';" na class desejada
     * @var String
     */
    protected $table = NULL;

    /**
     * Guarda o nome da primary key, por default é 'id'
     * Para definir uma outra primary key basta definir "protected $primary_key = 'PrimaryKey';" na class desejada
     * @var string
     */
    protected $primary_key = "id";
    
    /**
     * Guarda se a class trabalha com auto increment na sua primary key, por default é TRUE
     * Para definir que não deseja trabalhar com auto increment basta definir "protected $auto_increment;" na class desejada
     * @var boolean
     */
    protected $auto_increment = TRUE;

    /**
     * Guarda o valor utilizado para atribuir a primary key em caso de não ser utilizado auto increment, por default o UPhp define um valor criptgrafado e randomico utilizando o comando "sha1(uniqid(rand(), TRUE))"
     * Para definir seu próprio valor basta definir "protected $primary_key_value;" e especificar seu valor no metodo __construct() da class desejada ("$this->primary_key_value = 'valorDefinido';"), antes do construtor de parentesco
     * Ex.:
     * class Test extend ActiveRecord()
     * {
     *     protected $auto_increment;
     *     protected $primary_key_value;
     *     public function __construct()
     *     {
     *          $this->primary_key_value = 'valorDefinido';
     *          parent::__construct();
     *     }
     * }
     * 
     * @var String
     */
    protected $primary_key_value;

    /**
     * Guarda se a class trabalha com timeStamp, por default é TRUE
     * Para definir que não deseja trabalhar com timeStamp basta definir "protected $time_stamp;" na class desejada
     * @var boolean
     */
    protected $time_stamp = TRUE;

    protected $for_update = FALSE;

    /* BEGIN Manipulation Functions ***********************************************/

    public function create()
    {
        self::connect();
        $this->getSyntaxCreate();

        if (! empty($this->result)) {
            $this->setAttributes($this->oldValues);
            $pk = $this->primary_key;
            $this->$pk = $this->result;
            return TRUE;
        } else return FALSE;
        
        //var_dump($this->sql);
        //die;

        // BEGIN MODELO EXEMPLO
        # public function create($objeto = stdClass)
        //$this->resgatarTabela($objeto);
        //$this->getSyntaxCreate($objeto);
        //$this->execute(__FUNCTION__);
        // END EXEMPLO
    }

    // PADRAO NOVO COM RETORNO DE UM OBJETO DO TIPO INSTANCIADO
    public function update(Array $object_array = [])
    {
        // Codigo aqui
        self::connect();
        return $this;

        // BEGIN MODELO EXEMPLO
        # public function update(array $dados, $objeto = stdClass)
        $this->resgatarTabela($objeto);
        $this->dados = $dados;
        $this->getSyntaxUpdate($objeto);
        $this->execute(__FUNCTION__);
        // END EXEMPLO
    }

    // PADRAO NOVO COM RETORNO DE UM OBJETO DO TIPO INSTANCIADO
    public function save()
    {
        if ($this->for_update) return $this->update();
        return $this->create();
    }

    // PADRAO NOVO COM RETORNO DE UM OBJETO DO TIPO INSTANCIADO
    public function delete()
    {
        // Codigo aqui
        self::connect();
        return $this;

        // BEGIN MODELO EXEMPLO
        # public function delete($objeto = stdClass)
        $this->resgatarTabela($objeto);
        $this->getSyntaxDelete($objeto);
        $this->execute(__FUNCTION__);
        // END EXEMPLO
    }
    /* END Manipulation Functions *************************************************/
}