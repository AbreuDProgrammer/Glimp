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
        $username_query = $this->user_exists(array( 'username' => $user['username']));

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

        $username_exists = $this->user_exists(array('username' => $user['username']));

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

    // ------------------------------------------------------------ Modelo ------------------------------------------------------------
    /**
     * A partir daqui são as funcionalidades da classe modelo
     * que são seguem sempre o mesmo padrão, como o de login e logout.
     * 
     * Então é de extrema importância que os passos para ser feitas as 
     * consultas sigam uma ordem específica, dentro e fora da classe.
     * 
     * A ordem de qualquer pedido sempre sequirá uma mesma ordem:
     * 1 - Verificar se o user que esta sendo pedido existe
     * 2 - Se sim buscar pelo id desse user
     * 3 - Quando encontrado as operações seguintes devem ser feitas usando o id
     */

    /**
     * Essa funcionalidade é essencial para a classe,
     * ela procura num array de existe alguma informação que
     * possa ser usada para a pesquisa do user na DB,
     * se exstir retorna uma cláusula where com o modelo de 
     * pesquisa para a informação.
     */
    private function get_searchable_colunm(Array $userdata): ?Array
    {
        $where_clause = array();
        foreach(self::UNIQUE_DATA as $possible_key)
        {
            if(isset($userdata[$possible_key]))
            {
                $where_clause[$possible_key] = $userdata[$possible_key];
                break;
            }
        }
        return $where_clause ?? NULL;
    }

    // ------------------------------ Verificações ------------------------------
    /**
     * Essa aba separa as funcionalidades que ficam responsáveis apenas por
     * retornar TRUE or FALSE para verificações sobre os user.
     * 
     * Recomendado usar essas funcionalidades dentro da clausula if.
     */

    /**
     * Essa funcionalidade usa a consulta do id para verificar se 
     * foi retornado alguma coisa.
     */
    public function user_exists(Array $userdata): Bool
    {
        return $this->get_user_id($userdata) <> NULL;
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
     * Para isso percorre o array de datas unicas e 
     * verifica se alguma está no array passado,
     * quando acha insere no novo array.
     * 
     * Todas as outras consultas serão feitas pelo id do user
     * então essa funcionalidade é essencial para toda a classe.
     */
    private function get_user_id(Array $userdata): ?Int
    {
        $where = $this->get_searchable_colunm($userdata);

        if(!$where)
            return false;

        return $this->select('Users', 'user_id', $where)['user_id'] ?? NULL;
    }

    /**
     * Essa funcionalidade vai receber:
     * 1 - O user que está fazendo o pedido (user_asking).
     * 2 - O user que está sendo pedido (user_asked).
     * 
     * Vai verificar qual o nivel de relação entre eles e devolver
     * toda a informação que estiver no seu nível:
     * Public - Se os users não tiverem nenhuma relação.
     * Protected - Se os users forem amigos.
     * Private - Se os users forem os mesmos.
     * 
     * Password não é devolvida mesmo no private.
     * 
     * Essa funcionalidade deve ser usada nos controllers para
     * receber toda a informação possível de user para mostrar
     * ao outro user.
     */
    public function get_userdata(Array $user_asking, Array $user_asked): ?Array
    {
        if(!$this->user_exists($user_asking) || !$this->user_exists($user_asked))
            return NULL;

        $user_asking_id = $this->get_user_id($user_asking);
        $user_asked_id = $this->get_user_id($user_asked);

        $info_allowed = $this->get_user_data_permissions($user_asking_id, $user_asked_id);
        $info_allowed[] = 'user_id';

        $query = $this->select('Users', $info_allowed, array('user_id' => $user_asked_id));
        
        return $query ?? NULL;
    }

    public function get_data_permissions(Int $user_id_asked): ?Array
    {
        $permissions = $this->get('UserDataPermissions', array('user_id_data_permissions' => $user_id_asked));
        return $permissions ?? NULL;
    }

    private function get_user_data_permissions(Int $user_id_sender, Int $user_id_asked): ?Array
    {
        $permissions = $this->get('UserDataPermissions', array('user_id_data_permissions' => $user_id_asked));
        unset($permissions['user_id_data_permissions']);
        $relation = $this->get_users_relate($user_id_sender, $user_id_asked);

        $type = array();

        /**
         * Está certo sem os break's
         * quando o user for o próprio a pedir
         * tem todas as informações possiveis,
         * quando for amigo tem as protecteds e publics
         * e quando não for ninguém tem apenas public
         */
        switch($relation)
        {
            case 'same':
                $type[] = 'private';
            case 'friends':
                $type[] = 'protected';
            default:
                $type[] = 'public';
        }
        
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

    public function update_user_permissions(Array $permissions_data, Array $user_sender): Bool
    {
        if(!isset($permissions_data['user_id_data_permissions']))
            return FALSE;

        if(!$this->user_exists(array('user_id' => $permissions_data['user_id_data_permissions'])))
            return FALSE;

        if($permissions_data['user_id_data_permissions'] <> $user_sender['user_id'])
            return FALSE;
        
        $update = $this->update('UserDataPermissions', $permissions_data, array('user_id_data_permissions' => $permissions_data['user_id_data_permissions']));
        return $update <> NULL;
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