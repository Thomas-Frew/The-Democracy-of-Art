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