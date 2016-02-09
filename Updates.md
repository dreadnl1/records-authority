# Patching Notes #

## 2.0.0 ##
  * Base install

## 2.0.1 ##
  * enter the command "**`svn update`**" in your recordsAuthority folder or "**`svn checkout http://records-authority.googlecode.com/svn/trunk/ recordsAuthority`**"
  * Refer to http://codeigniter.com/user_guide/installation/upgrading.html
  * Run the SQL command on your data base
    * "**`CREATE INDEX last_activity_idx ON rm_sessions(last_activity); ALTER TABLE rm_sessions MODIFY user_agent VARCHAR(120);`**"