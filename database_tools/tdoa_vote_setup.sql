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