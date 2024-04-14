<?php
# OrganizrAuth plugin - a MantisBT plugin for delegating login to the web server
#

auth_reauthenticate();
access_ensure_global_level(config_get("manage_plugin_threshold"));

layout_page_header(plugin_lang_get('title'));
layout_page_begin();

print_manage_menu();
?>
<div class="cold-md-12 col-xs-12">
	<div class="space-10"></div>
	<div id="auth-config-div" class="form-container">
		<form action="<?php echo plugin_page('config') ?>" method="post" class="form-inline">
			<div class="widget-box widget-color-blue2">
				<div class="widget-header widget-header-small"><h4 class="widget-title lighter"><?php echo plugin_lang_get("config_title") ?></h4></div>
				<div class="widget-body">
					<div class="widget-main no-padding">
						<div class="table-responsive">
							<?php echo form_security_field("plugin_OrganizrAuth_config") ?>
							<table class="table table-striped table-bordered table-condensed"><tbody>
								<tr>
									<td class="category"><?php echo plugin_lang_get('organizr_cookie') ?></td>
									<td><input class="form-control" maxlength="75" name="organizr_cookie" value="<?php echo plugin_config_get('organizr_cookie') ?>" /></td>
								</tr>
								<tr>
									<td class="category"><?php echo plugin_lang_get('organizr_url') ?></td>
									<td><input class="form-control" maxlength="120" name="organizr_url" value="<?php echo plugin_config_get('organizr_url') ?>" /></td>
								</tr>
								<tr>
									<td class="category">
										<?php echo plugin_lang_get( 'organizr_access_level' ) ?>
									</td>
									<td>
										<select id="edit-access-level" name="organizr_access_level" class="input-sm"><?php
											$t_access_level = $t_user['access_level'];
											if( !MantisEnum::hasValue( config_get( 'access_levels_enum_string' ), $t_access_level ) ) {
												$t_access_level = config_get( 'default_new_account_access_level' );
											}
											print_project_access_levels_option_list( (int)$t_access_level ); ?>
										</select>
									</td>
								</tr>
							</tbody></table>
						</div>
					</div>
					<div class="widget-toolbox passing-8 clearfix">
						<input type="submit" class="btn btn-primary btn-white btn-round" value="<?php echo plugin_lang_get('action_update') ?>" />
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<?php
layout_page_end();
