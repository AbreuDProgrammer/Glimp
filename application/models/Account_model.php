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
    private const UNIQUE_DATA = array('user_id', 'username', 'email', 'phone');

    // Funcionalidade constructora de cada model
    public function constructor()
    {
        // Carrega o PasswordHash
        $this->load->helper('PasswordHash_helper');
        $this->PasswordHash = new PasswordHash(8);
    }

    // ------------------------------ Ações ------------------------------
    /**
     * Aqui serão quardadas as ações pedidas nos controller
     * Ações mais complexas que exigem outras chamadas e maior controle
     */

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
        $user_password = $this->get_password($user['username']);

        // Verifica se as palavras passes são as mesmas
        if(!$this->PasswordHash->CheckPassword($user['password'], $user_password))
            return false;

        // Recebe o id do user
        $user_id = $this->get_user_id(array('username' => $user['username']));

        // Informa que o login foi feito para a table users
        $this->set_is_logged(TRUE, $user_id);

        // Cria uma nova sessão ao user
        $this->set_session_id($user_id);
            
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

        // Recebe o id do user
        $user_id = $this->get_user_id(array('username' => $user['username']));

        // Informa que o login foi feito para a table users
        $this->set_is_logged(TRUE, $user_id);

        // Cria uma nova sessão ao user
        $this->set_session_id($user_id);

        // Cria as permissões de visualização dos dados
        $this->set_permissions_default($user_id);

        return $create_query; // Retorna true se funcionar e false se não
    }

    /**
     * Funcionalidade que prepara o logout
     */
    public function logout(Array|String $userdata): Void
    {
        // Informa que o logout foi feito para a table users
        $id = is_array($userdata) ? $userdata['user_id'] : $userdata;
        $this->set_is_logged(FALSE, $id);
        $this->set_session_id($id, 0);
    }

    // ------------------------------ Verificações ------------------------------
    /**
     * Essa aba separa as funcionalidades que ficam responsáveis apenas por
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
     * 
     * Quando a consulta for feita via array, apenas retorna se existir
     */
    private function user_exists(Array|String|Int $userdata, String $method): Bool
    {
        if(is_array($userdata))
            return $this->select('Users', 'user_id', $userdata) <> NULL;
        if(!in_array($method, self::UNIQUE_DATA))
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

        return $this->user_exists($user_id, 'user_id');
    }

    public function username_exists(Array|String $userdata): Bool
    {
        $username = is_array($userdata) ? $userdata['username'] : $userdata;
        
        return $this->user_exists($username, 'username');
    }

    public function email_exists(Array|String $userdata): Bool
    {
        $email = is_array($userdata) ? $userdata['email'] : $userdata;

        return $this->user_exists($email, 'email');
    }

    public function phone_exists(Array|Int $userdata): Bool
    {
        $phone = is_array($userdata) ? $userdata['phone'] : $userdata;
        
        return $this->user_exists($phone, 'phone');
    }

    /**
     * Essa funcionalidade verifica se o user existe, 
     * independentimente do tipo de data que for passado,
     * recomendo usar apenas nas funcionalidades que 
     * não sabemos qual tipo de consulta está sendo feita
     */
    public function userdata_exists(Array $userdata)
    {
        return $this->user_exists($userdata, 'phone');
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
        return $is_logged['is_logged'];
    }

    // ------------------------------ Consultas ------------------------------
    /**
     * Essa aba faz consultas a base de dados
     * para retornar informações sobre o user.
     * 
     * É recomendado fazer depois da verificação.
     * 
     * Sempre retorna NULL se o user não existir.
     * 
     * É usado para consultas públicas e privadas, para
     * saber diferenciar nas consultas gerais é sempre passado 
     * no parametro uma clausula where que pode ser com o username, email,
     * id ou phone e no segundo é passado o user session.
     * Se o user session for o mesmo que está sendo feita a consulta
     * retorna todos os dados desse user. Caso contrário é apenas retornado
     * os dados que o user permite na tabela de permissões
     */

    /**
     * Funcionalidade que retorna o id de qualquer user 
     * por alguma data única.
     * 
     * Todas as outras consultas serão feitas pelo id do user
     * então essa funcionalidade é essencial para toda a classe.
     */
    private function get_user_id(Array $where): ?Int
    {
        $clause = array_keys($where)[0];
        if(!in_array($clause, self::UNIQUE_DATA))
            return NULL;

        $id = $this->select('Users', 'user_id', $where);
        return $id['user_id'] ?? NULL;
    }
    
    /**
     * Para unificar as conultas, essa funcionalidade vai
     * verificar qual user quer recolher, qual o id ou username ou email ou phone
     * desse user e irá retornar toda a informação presente no user.
     * 
     * E mais importante ainda irá verificar se o user que está sendo pedido está loggado
     * e se sim se tem a mesma sessão que está sendo feito o pedido.
     * 
     * É passado nos parametros qual o metodo da clausula where,
     * e as informações do user que está fazendo a consulta.
     * 
     * As consultas só podem ser feitas por um user ativo na plataforma.
     */
    private function get_user(Array $where, Array $user_sender): ?Array
    {
        /**
         * Se o user que está fazendo a consulta não existe
         * retorna e para a funcionalidade aqui
         */
        if(!$this->user_id_exists($user_sender))
            return NULL;

        /**
         * Se o user que está sendo pedido não existir
         * retorna null e para a funcionalidade aqui
         */
        if(!$this->userdata_exists($where))
            return NULL;

        $user_asked_id = $this->get_user_id($where);
        $user_asking_id = $user_sender['user_id'];

        /** 
         * Se os ids não forem o mesmo não é o dono da conta 
         * que está logado.
         */
        if($user_asked_id <> $user_asking_id)
        {
            // NÃO É O DONO DA CONTA

            // Recebe a lista de permissões das datas do user para o user que está pedindo
            $ables_permissions = $this->get_user_data_permissions($user_asked_id, $user_asking_id);
            
            return $this->select('Users', $ables_permissions, $where);
        }
        else
        {
            $user = $this->get('Users', $where);
            unset($user['password']);
            return $user;
        }
    }

    /**
     * Funcionalidades que chamam o get_user_private com diferentes wheres
     */
    public function get_user_by_id(Array|String $userdata, Array $user_sender): ?Array
    {
        $user_id = is_array($userdata) ? $userdata['user_id'] : $userdata;
        return $this->get_user(array('user_id' => $user_id), $user_sender);
    }

    public function get_user_by_username(Array|String $userdata, Array $user_sender): ?Array
    {
        $username = is_array($userdata) ? $userdata['username'] : $userdata;
        return $this->get_user(array('username' => $username), $user_sender);
    }

    
    /**
     * Funcionalidades usadas apenas na própria classe,
     * todas as clausulas where resperam pelo id do user
     */
    private function get_session(Int $id): ?Int
    {
        $session_id = $this->select('Users', 'session_id', array('user_id' => $id));
        return $session_id['session_id'] ?? NULL;
    }
    private function get_user_data_permissions(Int $user_id_sender, Int $user_id_asked): ?Array
    {
        $permissions = $this->get('UserDataPermissions', array('user_id_data_permissions' => $user_id_asked));
        unset($permissions['user_id_data_permissions']);
        $relation = $this->get_users_relate($user_id_sender, $user_id_asked);

        $type = array();
        if($relation == 'same'){
            $type[] = 'private';
            $type[] = 'protected';
        }
        if($relation == 'friends')
            $type[] = 'protected';
        $type[] = 'public';

        $ables = array();
        foreach($permissions as $key => $value)
        {
            if(in_array($value, $type))
                $ables[] = $key;
        }
        return $ables ?? NULL;
    }
    private function get_users_relate(Int $user_id_sender, Int $user_id_asked): String
    {
        if($user_id_asked === $user_id_sender)
            return 'same';

        $query = $this->get('Friendships', array(
            'user_id_sender' => $user_id_sender,
            'user_id_recipient' => $user_id_asked
        ));
        if(is_null($query))
            $query = $this->get('Friendships', array(
                'user_id_sender' => $user_id_asked,
                'user_id_recipient' => $user_id_sender
            ));
        if(is_null($query))
            return 'strangers';
        return 'friends';
    }

    /**
     * Funcionalidades usadas apenas na própria classe,
     * as clausulas where podem esperar por qualquer coisa passada
     * qualquer coisa permitida na constante
     */
    private function get_password(Array|String $userdata): ?String
    {
        $username = is_array($userdata) ? $userdata['username'] : $userdata;

        $where = array(
            'username' => $username
        );
        $username_query = $this->select('Users', 'password', $where);

        return $username_query['password'] ?? NULL;
    }
    
    // ------------------------------ Atualizações ------------------------------
    /**
     * Funcionalidades para atualizar as informações do user.
     * 
     * Novamente é verificado se o user que está pedindo para fazer a atualização
     * é o user que está sendo editado.
     */    

    // Transforma o is_logged em TRUE ou FALSE
    private function set_is_logged(Bool $is_logged, Int $id): Void
    {
        $user = array();
        $user['is_logged'] = $is_logged;
        $this->update('Users', $user, array('user_id' => $id));
    }

    // Cria uma nova sessão para o user
    private function set_session_id(Int $id, ?Int $session = NULL): Void
    {
        if(!$session)
            $session = rand(100000, 10000000);
        $this->update('Users', array('session_id' => $session), array('user_id' => $id));
    }

    private function set_permissions_default(Int $id): Void
    {
        $default_data = array(
            'user_id_data_permissions' => $id
        );
        $this->insert('UserDataPermissions', $default_data);
    }

    /**
     * Atualiza todos os dados do user
     */
    public function update_user(Array $userdata, Array $user_sender): Bool
    {
        if($userdata['user_id'] <> $user_sender['user_id'])
            return FALSE;

        $update = $this->update('Users', $userdata, array('user_id' => $user_sender['user_id']));
        return $update;
    }
}