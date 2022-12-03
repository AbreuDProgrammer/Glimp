/* Tables captalizadas com primeira letra maiúscula */
CREATE TABLE Users (
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
  configs text, 
  PRIMARY KEY(userId)
);
CREATE TABLE Friendships (
  friendshipId int(11), 
  usersIds text, 
  start date, 
  chatId int(11), 
  PRIMARY KEY(friendshipId)
);
CREATE TABLE Invites (
  inviteId int(11), 
  userIdSender int(11), 
  userIdRecipient int(11), 
  dateSent date, 
  inviteFor varchar(255), 
  description varchar(255), 
  status varchar(80), 
  permissions text, 
  PRIMARY KEY(inviteId)
);
CREATE TABLE Chats (
  chatId int(11), 
  membersIds text, 
  chatType varchar(80), 
  leadersIds text, 
  messagesIds text, 
  permissions text,
  PRIMARY KEY(chatId)
);
CREATE TABLE Messages (
  messageId int(11), 
  message text, 
  userIdSender int(11), 
  time_sent datetime, 
  time_received datetime, 
  time_read datetime, 
  PRIMARY KEY(messageId)
);
CREATE TABLE Comments (
  commentId int(11), 
  message text, 
  time_sent datetime, 
  num_likes int(11), 
  usersIdsLiked text, 
  PRIMARY KEY(commentId)
);
CREATE TABLE Posts (
  postId int(11), 
  message text, 
  time_sent datetime, 
  num_likes int(11), 
  usersIdsLiked text, 
  PRIMARY KEY(postId)
);
CREATE TABLE Events (
  eventId int(11), 
  description text, 
  event_date datetime, 
  membersIds text, 
  leaderId int(11), 
  PRIMARY KEY(eventId)
);