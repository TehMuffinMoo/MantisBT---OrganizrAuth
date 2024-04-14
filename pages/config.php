<?php
# OrganizrAuth plugin - a MantisBT plugin for delegating auth to the web server.
#

form_security_validate("plugin_OrganizrAuth_config");
access_ensure_global_level(config_get("manage_plugin_threshold"));

/* Avoid touching timestamp if no change. */
function maybe_set_option($name, $value) {
	if ($value != plugin_config_get($name)) {
		plugin_config_set($name, $value);
	}
}

maybe_set_option("organizr_cookie", gpc_get_string("organizr_cookie", OFF));
maybe_set_option("organizr_url", gpc_get_string("organizr_url", OFF));
maybe_set_option("organizr_access_level", gpc_get_string("organizr_access_level", OFF));

form_security_purge("plugin_OrganizrAuth_config");
print_successful_redirect(plugin_page("config_page", true));
