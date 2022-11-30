# Rain

## Leonardo's PAP
A minha PAP de trata de uma rede social que tem coneções com varias outras plataformas como spotify, discord, steam, epic games e mais.

## Users
Todo que usam a aplicação têm de esta logado.

Todo user têm de ter:
1 - Um username único, alteravel que pode levar os caracteres especiais (! $ & - _ *).
2 - Uma palavra-passe com o mínimo de 8 caracteres e uma maiúscula.
3 - Um nome alteravel que devem levar uma letra maiúscula no início, não pode ter números, não podem ter caracteres especiais e não podem ter mais que uma maiúscula.
4 - Um email ou um número de telefone existente, prove que exista e funcione e que seja único por user.

Todo user pode ter:
1 - Um email se escolheu o número de telefone para verificação.
2 - Um telefone se escolheu o email para verficação.
3 - Mais do que um email como secundário para recuperação de conta.
4 - Uma data de aniversário
5 - Uma descrição do user

Para o programador, todo user têm de ter:
1 - Uma session para identificar quando fez login e quando fez logout.
2 - Um array de permissões para saber quais apps o user autorizou exibir.

Todos os users têm ligações com outros users por meio de amizade, onde podem limitar o outro para ver apenas a atividade que permitir.

## Friendship
Todo user pode ter ilimitados amigos.

Para cada amigo o user tem um chat de conversa, que permite textos, imagens, vídeos, publicações de diferentes plataformas.

Cada amizade tem permissões de vizualização que é padrão poder ver a vizualização.

## Chat
Cada conversa tem permissões, que definem se o outro pode editar ou apagar uma mensagem. Todas as permissões se aplicam a todos os users.

## Groups
Todo grupo tem pelo menos um líder, e um máximo de 10.

Todo grupo tem pelo menos dois users e no máximo 100 users.

Todo grupo pode ter uma descrição.

As permissões de visualizações de mensagem de grupo são particulares, o que significa que se um user1 estiver num grupo com o user2 que não
está permitido de vizualizar quando a mensagem foi lida, um user3 ainda poderá ver.

## Posts
Todo post têm de ter o user que publicou, o dia e a hora publicada.

Os posts podem ser:
1 - Posts de texto.
2 - Posts de Imagens com descrição.
3 - Posts de Vídeos com descrição.

Todos os posts têm configuração definidas antes de postar como:
1 - Quem curtiu vizivel.
2 - Número de likes vizivel.
3 - Comentários habilitados.

Todos os posts têm:
Para todo o público -> Uma área para comentários, número de likes do post, opção de encaminhar para outro amigo.
Para o dono -> Manipulação total sobre os comentários, vizualização do número de likes e dos usuários que gostaram.

## Comments
Todo comentário funciona como um post de texto.

Os comentários podem ter likes e o número de likes é público.

O dono do comentáro pode ver quem gostou.

Os comentários podem ser apagados pelo dono do post.

## Convites
Todos os convites têm as permissões já configuradas depententes do motivo do convite.

Convite de amizade tem permissões de vizualização de mensagem e vizualização de atividades.

Convites de grupos tem as permissões do chat e os membros do grupo.

## Eventos
Eventos são momentos marcados no calendário que têm titulo, descrição, data, membros, lugar.

Os membros podem ser no mínimo um e sem máximo.

Para adicionar um membro um convite será enviado com toda a informação.