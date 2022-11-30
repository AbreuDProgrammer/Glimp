/* Tables captalizadas com primeira letra maiúscula */

CREATE TABLE Users
(
    userId int(11),
    username varchar(255),
    password varchar(255),
    email varchar(255),
    phone int(9),
    name varchar(255),
    email_extra varchar(255),
    birthday date,
    description text,
    friendshipsIds text,
    sessionId int,
    logsDetails text,
    appsPermissions text,
    configs text
);

CREATE TABLE Friendships
(
    friendshipId int(11),
    usersIds text,
    start date,
    permissions text
);

CREATE TABLE Invites
(
    inviteId int(11),
    userIdSender int(11),
    userIdRecipient int(11),
    dateSent date,
    inviteFor varchar(255),
    description varchar(255),
    status varchar(80),
    permissions text
);

