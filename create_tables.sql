CREATE TABLE USER (
	id INT PRIMARY KEY AUTO_INCREMENT,
	username VARCHAR(60) UNIQUE NOT NULL,
	name VARCHAR(30) NOT NULL,
	password VARCHAR(30) NOT NULL,
	image VARCHAR(30),
	status BOOLEAN NOT NULL
);



-- CREATE TABLE GROUP_CHAT(
-- 	id INT PRIMARY KEY AUTO_INCREMENT,
-- 	group_chat_name VARCHAR(30) DEFAULT 'chat_name',
-- 	group_admin INT NOT NULL,
-- 	FOREIGN KEY (group_admin) 
--         REFERENCES USER(id)
--         ON DELETE CASCADE



-- );

CREATE TABLE MESSAGES(
	id INT PRIMARY KEY AUTO_INCREMENT,
	content VARCHAR(100) NOT NULL,
	send_time TIMESTAMP, 
	seen_time TIMESTAMP,
	sent_to INT,
	FOREIGN KEY (sent_to) 
        REFERENCES USER(id)
        ON DELETE CASCADE,
	sent_from INT,
	FOREIGN KEY (sent_from) 
        REFERENCES USER(id)
        ON DELETE CASCADE
 --    group_chat_id INT, 
	-- FOREIGN KEY(group_chat_id)
	-- 	REFERENCES GROUP_CHAT(id) 
);

-- CREATE TABLE USER_GROUP_CHAT(
-- 	id INT PRIMARY KEY AUTO_INCREMENT,
-- 	user INT,
-- 	FOREIGN KEY (user) 
--         REFERENCES USER(id)
--         ON DELETE CASCADE,
-- 	chat INT,
-- 	FOREIGN KEY (chat) 
--         REFERENCES GROUP_CHAT(id)
--         ON DELETE CASCADE 


-- );

CREATE TABLE BLOCK_LIST(
	id INT PRIMARY KEY AUTO_INCREMENT, 
	user_blocked INT NOT NULL,
	FOREIGN KEY (user_blocked) 
        REFERENCES USER(id)
        ON DELETE CASCADE,
	blocked_by INT NOT NULL,
	FOREIGN KEY (blocked_by) 
        REFERENCES USER(id)
        ON DELETE CASCADE


  );

CREATE TABLE INVITATION(
	id INT PRIMARY KEY AUTO_INCREMENT,
	request_sender INT NOT NULL, 
	FOREIGN KEY (request_sender)
		REFERENCES USER(id)
		ON DELETE CASCADE,
	request_acceptor INT NOT NULL,
	FOREIGN KEY (request_acceptor)
		REFERENCES USER(id)
		ON DELETE CASCADE,

	friend BOOLEAN DEFAULT 0


);

CREATE TABLE FRIENDSHIP(
	id INT PRIMARY KEY AUTO_INCREMENT, 
	user1 INT NOT NULL, 
	FOREIGN KEY (user1)
		REFERENCES USER(id)
		ON DELETE CASCADE,
	user2 INT NOT NULL, 
	FOREIGN KEY(user2)
		REFERENCES USER(id)
		ON DELETE CASCADE

);