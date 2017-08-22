# max-redirection
A simple plugin for redirection. Detecting incoming user country ID by IP address, and redirect it if there is a matching country ID.

When this plugin is activated, you should be able to see a new custom post type name Max Redirection. Just create a new post, select the country to redirect, and fill in the text field for target URL. Incoming user should now redirected to the corresponding url you have given.

Currently, you will also be redirected if your url path does not include wp-login.php or wp-admin