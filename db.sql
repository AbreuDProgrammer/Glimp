/* Tabela dos users */
CREATE TABLE Users (
  user_id int(11) AUTO_INCREMENT, /* Id único de cada user AI */
  username varchar(255), /* Username único de cada user, alterável */
  password varchar(255), /* Password sha256 com pelo menos 1 número e uma maiúscula */
  email varchar(255), /* Email único de cada user com verificação, alterável */
  phone int(9), /* Número de telemóvel único, alterável */
  name varchar(255), /* Nome de cada user, sem números */
  email_extra varchar(255), /* Email reserva único de cada user com verificação, alterável */
  birthday date, /* Data do aniversário */
  description text, /* Descrição do user */
  session_id int, /* Sessão salva quando iniciado em algum browser */
  logs_details text, /* Histórico de logins e logouts array json_encode */
  apps_permissions text, /* Permissões que o user liberou para com outros apps array json_encode */
  configs text, /* Configurações dos users sobre qualquer definição salva array json_encode */
  PRIMARY KEY(user_id)
);

/** 
 * Tabela que controla todos os users bloqueados por outro user 
 * Se um user desbloqueia o outro a coluna não é apagada, o status muda
 * Se esse mesmo user voltar a bloquea-lo, o status é mudado novamente
 */
CREATE TABLE UserBlocks (
  user_id_sender int(11), /* Id do user que fez o block */
  user_id_blocked int(11), /* Id do user que foi bloqueado */
  block_status varchar(80) /* O status do block (blocked, accessible) */
);

/** 
 * Tabela que controla todos os grupos bloqueados por outro user 
 * Se um user desbloqueia o grupo a coluna não é apagada, o status muda
 * Se esse mesmo user voltar a bloquea-lo, o status é mudado novamente
 */
CREATE TABLE GroupBlocks (
  user_id_sender int(11), /* Id do user que fez o block */
  group_id_blocked int(11), /* Id do grupo que foi bloqueado */
  block_status varchar(80) /* O status do block (blocked, accessible) */
);

/* Tabela que controla todo login e logout dos users */
CREATE TABLE Logs (
  user_id_log int(11), /* Id do user que fez o login */
  when_logged_in datetime, /* O dia e a hora em que o user fez o login */
  when_logged_out datetime /* O dia e a hora em que o user fez o logout */
);

/**
 * Tabela que controla as amizades entre users 
 * Um pedido de amizade é escrito nessa tabela com o friendship_status de waiting
 * Quando aceito o friendship_status muda para friends e o when_started é preenchido
 * Se for recusado continua salvo na tabela a tentativa com o friendship_status refused
 * Não é possivel fazer um pedido de amizade para o mesmo user novamente enquanto no waiting
 */
CREATE TABLE Friendships (
  friendship_id int(11) AUTO_INCREMENT, /* Id da ligação de amizade */
  user_id_sender int(11), /* Id do user que enviou o pedido de amizade */
  user_id_recipient int(11), /* Id do user que recebeu o pedido de amizade */
  friendship_status varchar(80), /* Status da amizade (waiting, friends, best_friends, refused) */
  when_started date, /* Data em que o pedido de amizade foi aceito */
  chat_id int(11), /* Id do chat da amizade */
  PRIMARY KEY(friendship_id)
);

/**
 * Tabela que controla todos os chats
 * As mensagens têm o Id do chat, a chave está no N
 */
CREATE TABLE Chats (
  chat_id int(11) AUTO_INCREMENT, /* Id da conversa que pode ser de amizade ou grupo */
  chat_type varchar(80), /* Tipo para diferenciar se é um chat de amizade ou de grupo */
  permissions text, /* Permissões da conversa array json_encode */
  PRIMARY KEY(chat_id)
);

/**
 * Tabela que controla as mensagens de chats
 * Para controlar quando foi recebida e lida por varios users existe outra tabela ReceivedMessages e ReadMessages
 */
CREATE TABLE Messages (
  message_id int(11) AUTO_INCREMENT, /* O Id AI da mensagem */
  message_info text, /* A mensagem txt, img ou video do user json_encode */
  user_id_sender int(11), /* Id do user que enviou a mensagem */
  time_sent datetime, /* O ano, mês, dia, hora, minuto e segundo que a mensagem foi enviada */
  PRIMARY KEY(message_id)
);

/* Controla as mensagens recebidas */
CREATE TABLE ReceivedMessages (
  message_id_received int(11), /* Id da mensagem que foi recebida */
  user_id_recipient int(11), /* Id do user que recebeu essa mensagem */
  when_received datetime /* O dia e a hora que a mensagem foi recebida */
);

/* Controla as mensagens lidas */
CREATE TABLE ReadMessages (
  message_id_read int(11), /* Id da mensagem que foi lida */
  user_id_recipient int(11), /* Id do user que leu essa mensagem */
  when_received datetime /* O dia e a hora que a mensagem foi lida */
);

/**
 * Os grupos podem fazer publicações entre eles como no facebook
 * Os grupos têm um lider owner e podem ter outros adm
 * Os grupos têm um chat em que todos estão inclusos
 */
CREATE TABLE Groups (
  group_id int(11) AUTO_INCREMENT, /* Id do grupo AI */
  owner_id int(11), /* Id do user que criou o grupo */
  adm_ids text, /* Ids dos users que receberam permissões de adm */
  group_description text, /* Descrição do grupo */
  when_started datetime, /* O dia e a hora em que o grupo foi criado */
  PRIMARY KEY(group_id)
);

/**
 * Configurações do grupo
 */
CREATE TABLE GroupConfig (
  group_config_id int(11), /* O grupo referente as configurações */
  group_visible boolean, /* Define se é possível ver o grupo por qualquer user */
  posts_visible boolean /* Define se os posts do grupo são visíveis */
);

/* Controla todos os membros dos grupo */ 
CREATE TABLE GroupsMembers(
  user_id_member int(11), /* Id do user que é membro de um grupo */
  group_id int(11), /* Id do grupo que é membro */
  when_joined datetime /* Quando entrou no grupo */
);

/* Controla todos os adm dos grupos */
CREATE TABLE GroupsAdm (
  user_id_adm int(11), /* Id do user que é admin de algum grupo */
  group_id int(11), /* Id do grupo que é adm */
  when_elected datetime /* Quando foi elegido adm desse grupo */
);

/**
 * Controla os pedidos para fazer parte do grupo privado
 * Apenas os adm podem aceitar
 * Não é possivel pedir para um grupo que ainda não aceitou ou recusou um convite
 */
CREATE TABLE GroupsRequest (
  group_request_id int(11), /* Id do convite que foi enviado */
  user_id_sender int(11), /* Id do user que fez o convite */
  group_id_asked int(11), /* Id do grupo que recebeu o convite */
  when_requested datetime, /* Quando pediu para entrar */
  request_description varchar(255), /* Uma descrição opcional do convite */
  request_status varchar(80), /* O status do convite (waiting, refused, accepted) */
  PRIMARY KEY(group_request_id)
);

/**
 * Controla os convites para fazer parte do grupo privado
 * Apenas os adm podem convidar alguém
 * Não é possivel pedir para um user que ainda não aceitou ou recusou um convite
 */
CREATE TABLE GroupsInvite (
  group_invite_id int(11), /* Id do convite que foi enviado */
  user_id_sender int(11), /* Id do user que fez o convite */
  group_id_asked int(11), /* Id do grupo que recebeu o convite para fazer parte */
  user_id_recipient int(11), /* Id do user que recebeu o convite */
  when_invited datetime, /* Quando pediu para entrar */
  invite_description varchar(255), /* Uma descrição opcional do convite */
  invite_status varchar(80), /* O status do convite (waiting, refused, accepted) */
  PRIMARY KEY(group_invite_id)
);

/**
 * Os comentarios podem ser curtidos
 * Os comentários têm uma lista de likes que podem ser vistas por todos
 * Comentários podem ser apagados pelo(s) dono(s) da publicação ou pelo dono do comentario
 */
CREATE TABLE Comments (
  comment_id int(11) AUTO_INCREMENT, /* O Id AI do comentario */
  comment_message text, /* A mensagem desse comentario */
  post_id_commented int(14), /* O post em que foi comentado */
  time_sent datetime, /* A hora que foi comentado */
  PRIMARY KEY(comment_id)
);

/* Tabela que relaciona os comentarios com os likes */
CREATE TABLE CommentsLikes (
  comment_id_liked int(11), /* O Id do comentario relacionado */
  user_id_liked int(11), /* O Id do user que deu o like */
  time_liked datetime /* Quando o comentario foi curtido */
);

/**
 * Tabela de todos os posts 
 * Os posts podem ser texto, imagem ou video
 * Para o começo da app vou usar somente o de mensagem
 */
CREATE TABLE Posts (
  post_id int(14) AUTO_INCREMENT, /* O Id do post AI */
  owner_post_id int(11), /* O Id do user que fez o post */
  post_message text, /* O texto do post */
  time_sent datetime, /* Quando foi publicado */
  PRIMARY KEY(post_id)
);

/* Tabela que relaciona os posts com os likes */
CREATE TABLE PostsLikes (
  post_id_liked int(14), /* O Id do post relacionado */
  user_id_liked int(11), /* O Id do user que deu o like */
  time_liked datetime /* Quando o post foi curtido */
);

/**
 * Tabela que controla os eventos
 * Eventos são criados por users e podem pertencer a grupos
 * Eventos criados que pertencem a grupos avisam a todos os membros desse evento
 * O(s) dono(s) do grupo aceitam ou não o evento pertencer ao grupo, podem altera-lo e deleta-lo
 * Eventos particulares podem ser públicos ou privados, e pode conter mais membros apenas ao evento sem a necessidade de um grupo
 * Impossivel alterar um evento depois de ter acontecido
 */
CREATE TABLE Events (
  event_id int(11) AUTO_INCREMENT, /* O Id do evento AI*/
  event_description text, /* Uma descrição do evento */
  event_date datetime, /* O dia que o evento irá acontecer */
  owner_event_id int(11), /* O Id do user que criou o evento */
  group_id_belongs int(11), /* Se o evento pertence a um grupo indicará o Id desse grupo */
  PRIMARY KEY(event_id)
);

/* Tabela que relaciona os membros de um evento privado */
CREATE TABLE EventMembers (
  event_id int(11), /* O Id do evento */
  member_event_id int(11), /* O Id do user que é membro do evento */
  when_joined datetime, /* A hora que o user entrou no evento */
  PRIMARY KEY(event_id)
);

/**
 * Controla os pedidos para entrar em um evento 
 * Quando é pedido para entrar quero saber quem pediu e para qual evento
 * Eventos em grupos é necessário que o user faça parte do grupo
 */
CREATE TABLE EventRequest (
  event_request_id int(11), /* Id do convite que foi pedido para entrar */
  user_id_sender int(11), /* Id do user que fez o convite */
  event_id_asked int(11), /* Id do evento que pediu para participar */
  when_requested datetime, /* Quando pediu para entrar */
  request_description varchar(255), /* Uma descrição opcional do convite */
  request_status varchar(80), /* O status do convite (waiting, refused, accepted) */
  PRIMARY KEY(event_request_id)
);

/**
 * Controla os pedidos convidando alguém para entrar num evento 
 * Quando se convida alguém é preciso saber quem convidou, quem foi convidado e qual o evento
 * Eventos em grupos é necessário que o user faça parte do grupo
 */
CREATE TABLE EventInvite (
  event_invite_id int(11), /* Id do convite que foi enviado */
  user_id_sender int(11), /* Id do user que fez o convite */
  event_id_asked int(11), /* Id do evento que pediu para entrar */
  user_id_recipient int(11), /* Id do user que recebeu o convite */
  when_invited datetime, /* Quando pediu para entrar */
  invite_description varchar(255), /* Uma descrição opcional do convite */
  invite_status varchar(80), /* O status do convite (waiting, refused, accepted) */
  PRIMARY KEY(event_invite_id)
);