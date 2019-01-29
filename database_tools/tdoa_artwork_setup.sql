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