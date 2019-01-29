CREATE TABLE IF NOT EXISTS tdoa_users (
	user_id 		INT						UNSIGNED NOT NULL AUTO_INCREMENT,
	first_name		VARCHAR(20)				NOT NULL,
	last_name		VARCHAR(40)				NOT NULL,
	email			VARCHAR(60)				NOT NULL,
	pass			VARCHAR(256)			NOT NULL,
	reg_date		DATETIME				NOT NULL,
	
	lively_mode		ENUM("T", "F")			NOT NULL,
	artworks		INT						DEFAULT 0 NOT NULL,
	karma			INT						DEFAULT 0 NOT NULL,
	
	PRIMARY KEY(user_id),
	UNIQUE(email),
	
	INDEX(first_name),
	INDEX(last_name),
	INDEX(lively_mode)
);

CREATE TABLE IF NOT EXISTS tdoa_artworks
(
	artwork_id			INT					UNSIGNED NOT NULL AUTO_INCREMENT,
	artwork_name		VARCHAR(40)			NOT NULL,
	artwork_desc		VARCHAR(400)		NOT NULL,
	artwork_img			VARCHAR(260)		NOT NULL, 								/* The maximum directory length in Windows is 260 characters */
	
	author_id			INT					UNSIGNED NOT NULL,
	first_name			VARCHAR(20)			NOT NULL,
	last_name			VARCHAR(40)			NOT NULL,
	
	karma				INT					NOT NULL,
	review_desc			VARCHAR(60)			NOT NULL,
	upload_date			DATETIME			NOT NULL,

	PRIMARY KEY(artwork_id),
	UNIQUE(artwork_img),
	
	FOREIGN KEY(author_id) REFERENCES tdoa_users(user_id),
	FOREIGN KEY(first_name) REFERENCES tdoa_users(first_name),
	FOREIGN KEY(last_name) REFERENCES tdoa_users(last_name),
	
	INDEX(artwork_id),
	INDEX(author_id),
	INDEX(first_name),
	INDEX(last_name)
);

CREATE TABLE IF NOT EXISTS tdoa_votes
(
	vote_id				INT											UNSIGNED NOT NULL AUTO_INCREMENT,
	vote_type			ENUM("Upvote", "Downvote", "Neutral")		NOT NULL,

	author_id			INT											UNSIGNED NOT NULL,
	artwork_id			INT											UNSIGNED NOT NULL,
	
	user_id				INT											UNSIGNED NOT NULL,
	vote_date			DATETIME									NOT NULL,

	PRIMARY KEY(vote_id),
	
	FOREIGN KEY(author_id) REFERENCES tdoa_artworks(author_id),
	FOREIGN KEY(artwork_id) REFERENCES tdoa_artworks(artwork_id),
	FOREIGN KEY(user_id) REFERENCES tdoa_users(user_id),

	
	INDEX(artwork_id),
	INDEX(user_id)
);