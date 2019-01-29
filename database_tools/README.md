# Database Tools
This is the folder which stores all database tools needed to set up The Democracy of Art. If you're just getting started, the only file you need to run is `tdoa_complete_setup.sql`.

## Files:
If you need more information, here is the function of each individual file:
* `tdoa_artwork_setup.sql`: Sets up `tdoa_artworks`. Cannot be used if `tdoa_users` is not set up prior.
* `tdoa_complete_setup.sql`: Sets up all tables: `tdoa_users`, `tdoa_artworks` and `tdoa_votes`.
* `tdoa_complete_wipe.sql`: Deletes all tables.
* `tdoa_facotry_reset.sql`: Resets all tables (deletes them and sets them up again).
* `tdoa_user_setup.sql`: Sets up `tdoa_users`.
* `tdoa_vote_setup.sql`: Sets up `tdoa_votes`. Cannot be used if `tdoa_users` and `tdoa_artworks` are not set up prior.
