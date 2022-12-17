<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo que relaciona todas as tabelas sobre o user.
 * As tabelas são as do User, UserAppsPermissions e UserBlocks.
 * Esse modelo fica responsável por controlar as 3 tabelas
 */
class Account_model extends My_model 
{
    /**
     * Constante que guarda as possíveis consultas para
     * verificação do user
     */
    private const SELECTS_POSSIBLES = array('user_id', 'username', 'email', 'phone');

    // Funcionalidade constructora de cada model
    public function constructor()
    {
        // Carrega o PasswordHash
        $this->load->helper('PasswordHash_helper');
        $this->PasswordHash = new PasswordHash(8);
    }

    /**
     * Verifica se o username e password batem com um user
     * 
     * Retorna false se o user não existir ou se a palavra-passe não estiver certa
     * 
     * Retorna os dados do user se existir e estiver certa
     * 
     * Cria uma nova sessão ao user
     */
    public function login(Array $user): Bool|Array
    {
        if(!isset($user['username']) || !isset($user['password']))
            return false;
            
        // Cria a query onde busca apenas pelo username
        $username_query = $this->username_exists($user['username']);

        // Verifica se o user existe
        if(!$username_query)
            return false;
        
        // Recebe a password do user
        $user_password = $this->get_user_password($user['username']);

        // Verifica se as palavras passes são as mesmas
        if(!$this->PasswordHash->CheckPassword($user['password'], $user_password))
            return false;

        // Informa que o login foi feito para a table users
        $this->set_is_logged(TRUE, $user['username']);

        // Cria uma nova sessão ao user
        $this->set_new_session(array('username' => $user['username']));
            
        // Retorna os dados da DB todos para o login
        return $this->get('Users', array('username' => $user['username']));
    }

    /**
     * Cria o user para a DB
     * O ID é AI
     * Set do is_logged para TRUE
     * Verifica se o username já existe
     */
    public function create_account(Array $user): Bool
    {
        if(!isset($user['username']) || !isset($user['password']))
            return false;

        $username_exists = $this->username_exists($user['username']);

        if($username_exists)
            return false;
        
        $user['password'] = $this->PasswordHash->HashPassword($user['password']);

        $create_query = $this->insert('Users', $user);

        if(!$create_query)
            return false;

        // Informa que o login foi feito para a table users
        $this->set_is_logged(TRUE, $user['username']);

        // Cria uma nova sessão ao user
        $this->set_new_session(array('username' => $user['username']));

        return $create_query; // Retorna true se funcionar e false se não
    }

    /**
     * Funcionalidade que prepara o logout
     */
    public function logout(Array|String $userdata): Void
    {
        // Informa que o logout foi feito para a table users
        $username = is_array($userdata) ? $userdata['username'] : $userdata;
        $this->set_is_logged(FALSE, $username);
    }

    /**
     * Funcionalidade que atualiza os dados do user
     * Primeiro é passado qual o id do user, depois os dados em 
     * array associativo
     */
    public function update_user(Int $user_id, Array $userdata): Bool
    {
        // Verifica se o user existe
        if(!$this->user_id_exists($user_id))
            return false;

        $user = $this->get_user_by_id($user_id);

        $update = $this->update('Users', $userdata, array('user_id' => $user_id));
        return $update;
    }

    // ------------------------------ Verificações ------------------------------
    /**
     * Essa aba separa as funcionalidades que ficam resposnáveis apenas por
     * retornar TRUE or FALSE para verificações sobre os user
     * 
     * Recomendado usar essas funcionalidades dentro da clausula if
     */

    /**
     * Funcionalidade que consulta a tabela
     * 
     * Usada por outras funcionalidades para controlar em um
     * só lugar as ligações por meio de constantes
     * 
     * Apenas aceita String ou Int como primeiro parametro,
     * e o segundo é passado e verificado na constante
     * 
     * Sempre consuilta pelo user_id porque é impossível de ser alterado
     */
    private function user_exists(String|Int $userdata, String $method): Bool
    {
        if(!in_array($method, self::SELECTS_POSSIBLES))
            return false;
        return $this->select('Users', 'user_id', array($method => $userdata)) <> NULL;
    }

    /**
     * Funcionalidades que redirecionam à mesma funcionalidade
     * 
     * Verificam se o username ou user_id ou email ou phone 
     * existem e pertencem à algum user.
     * 
     * É possivel passar a userdata como Array associativo ou como String
     */
    public function user_id_exists(Array|Int $userdata): Bool
    {
        $user_id = is_array($userdata) ? $userdata['user_id'] : $userdata;

        if(is_null($user_id))
            return false;

        return $this->user_exists($user_id, 'user_id');
    }
    public function username_exists(Array|String $userdata): Bool
    {
        $username = is_array($userdata) ? $userdata['username'] : $userdata;

        if(is_null($username))
            return false;
        
        return $this->user_exists($username, 'username');
    }
    public function email_exists(Array|String $userdata): Bool
    {
        $email = is_array($userdata) ? $userdata['email'] : $userdata;

        if(is_null($email))
            return false;
        
        return $this->user_exists($email, 'email');
    }
    public function phone_exists(Array|Int $userdata): Bool
    {
        $phone = is_array($userdata) ? $userdata['phone'] : $userdata;

        if(is_null($phone))
            return false;
        
        return $this->user_exists($phone, 'phone');
    }

    /**
     * Funcionalidade para verificar se o user está logado.
     * 
     * Como padrão apenas retorna TRUE or FALSE
     * 
     * Array associativo para verificar como 'username' => 'leo'
     */
    private function is_logged(Array $where): Bool
    {
        $is_logged = $this->select('Users', 'is_logged', $where);
        return $is_logged[0]['is_logged'];
    }

    // ------------------------------ Consultas ------------------------------
    /**
     * Essa aba faz consultas a base de dados
     * para retornar informações sobre o user
     * 
     * É recomendado fazer depois da verificação
     * 
     * Sempre retorna NULL se o user não existir se passar
     * pelas verificações
     */

    /**
     * É apenas uma consulta utilizada na própria classe, nunca fora
     * então foi criada aqui
     */
    private function get_session(Array $where): Int|NULL
    {
        $session_id = $this->select('Users', 'session_id', $where);
        return $session_id[0]['session_id'] ?? NULL;
    }

    // --------------- Consultas públicas ---------------
    /**
     * Aqui são feitas as consultas que todo o público pode ver.
     * 
     * São retornadas as informações dependendo das 
     * permissões de vizualização que o user escolheu.
     */

    /**
     * Funcionalidade que une todas as outras dessa aba para a consulta
     */
    private function get_user(Array $where): Array|NULL
    {
        //! Ainda é preciso alterar muito
        return $this->get('Users', $where) ?? NULL;
    }

    /**
     * Funcionalidades que chamam o get_user_private com diferentes wheres
     */
    public function get_user_by_id(Array|String $userdata): Array|NULL
    {
        $user_id = is_array($userdata) ? $userdata['user_id'] : $userdata;
        return $this->get_user(array('user_id' => $user_id));
    }
    public function get_user_by_username(Array|String $userdata): Array|NULL
    {
        $username = is_array($userdata) ? $userdata['username'] : $userdata;
        return $this->get_user(array('username' => $username));
    }

    // --------------- Consultas privadas ---------------
    /**
     * Aqui são feitas as consultas que apenas o dono pode ver.
     * 
     * É retornado tudo sobre o user, menos a palavra-passe, que está
     * encriptada, por isso aqui dentro temos a funcionalidade de
     * atualizar a palavra-passe.
     * 
     * É sempre verificado se o user está loggado e se a sessão
     * que está ativa fazendo alguma consulta é a sessão que está 
     * ativa no user em específico.
     * 
     * Todas as funcionalidades que retornam informações privadas
     * levam o sufixo _private no nome da funcionalidade.
     */
    
    /**
     * Para unificar as conultas, essa funcionalidade vai
     * verificar qual user quer recolher, qual o id ou username ou email ou phone
     * desse user e irá retornar toda a informação presente no user.
     * 
     * E mais importante ainda irá verificar se o user que está sendo pedido está loggado
     * e se sim se tem a mesma sessão que está sendo feito o pedido.
     * 
     * É passado nos parametros qual o metodo da clausula where,
     * e as informações do user que está fazendo a consulta
     */
    private function get_user_private(Array $where, Array $user_sender): Array|NULL
    {
        if(!$this->user_id_exists($user_sender))
            return NULL;

        if(!$this->is_logged(array('user_id' => $user_sender['user_id'])) || !$this->is_logged($where))
            return NULL;

        $user_asked_session = $this->get_session($where);
        $user_asking_session = $this->get_session(array('user_id' => $user_sender['user_id']));

        if($user_asked_session !== $user_asking_session)
            return NULL;

        return $this->get('Users', $where);
    }

    /**
     * Funcionalidades que chamam o get_user_private com diferentes wheres
     */
    public function get_user_by_id_private(Array|String $userdata, Array $user_sender): Array|NULL
    {
        $user_id = is_array($userdata) ? $userdata['user_id'] : $userdata;
        return $this->get_user_private(array('user_id' => $user_id), $user_sender);
    }
    public function get_user_by_username_private(Array|String $userdata, Array $user_sender): Array|NULL
    {
        $username = is_array($userdata) ? $userdata['username'] : $userdata;
        return $this->get_user_private(array('username' => $username), $user_sender);
    }

    

    /**
     * Funcionalidade para recber a palavra-passe do user pelo username
     * Usado no login para trazer apenas a password para testar com a passada
     */
    private function get_user_password(Array|String $userdata): String|NULL
    {
        $username = is_array($userdata) ? $userdata['username'] : $userdata;

        $where = array(
            'username' => $username
        );
        $username_query = $this->select('Users', 'password', $where);

        return $username_query[0]['password'] ?? NULL;
    }
    
    // ------------------------------ Atualizações ------------------------------
    /**
     * Funcionalidades para atualizar as informações do user.
     * 
     * Novamente é verificado se o user que está pedindo para fazer a atualização
     * é o user que está sendo editado.
     */    

    // Transforma o is_logged em TRUE ou FALSE
    private function set_is_logged(Bool $is_logged, String $username): Void
    {
        $user = array();
        $user['is_logged'] = $is_logged;
        $this->update('Users', $user, array('username' => $username));
    }

    // Cria uma nova sessão para o user
    private function set_new_session(Array $where): Void
    {
        $session_id = rand(100000, 10000000);
        $this->update('Users', array('session_id' => $session_id), $where);
    }
}