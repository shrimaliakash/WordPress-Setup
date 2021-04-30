<?php
class StarterBlog_Customizer_Control_Icon extends StarterBlog_Customizer_Control_Base {
	static function field_template() {
		echo '<script type="text/html" id="tmpl-field-starterblog-icon">';
		self::before_field();
		?>
		<#
		if ( ! _.isObject( field.value ) ) {
			field.value = { };
		}
		#>
		<?php echo self::field_header(); ?>
		<div class="starterblog-field-settings-inner">
			<div class="starterblog--icon-picker">
				<div class="starterblog--icon-preview">
					<input type="hidden" class="starterblog-input starterblog--input-icon-type" data-name="{{ field.name }}-type" value="{{ field.value.type }}">
					<div class="starterblog--icon-preview-icon starterblog--pick-icon">
						<# if ( field.value.icon ) {  #>
							<i class="{{ field.value.icon }}"></i>
						<# }  #>
					</div>
				</div>
				<input type="text" readonly class="starterblog-input starterblog--pick-icon starterblog--input-icon-name" placeholder="<?php esc_attr_e( 'Pick an icon', 'starter-blog' ); ?>" data-name="{{ field.name }}" value="{{ field.value.icon }}">
				<span class="starterblog--icon-remove" title="<?php esc_attr_e( 'Remove', 'starter-blog' ); ?>">
					<span class="dashicons dashicons-no-alt"></span>
					<span class="screen-reader-text">
					<?php _e( 'Remove', 'starter-blog' ); ?></span>
				</span>
			</div>
		</div>
		<?php
		self::after_field();
		echo '</script>';
		?>
		<div id="starterblog--sidebar-icons">
			<div class="starterblog--sidebar-header">
				<a class="customize-controls-icon-close" href="#">
					<span class="screen-reader-text"><?php _e( 'Cancel', 'starter-blog' ); ?></span>
				</a>
				<div class="starterblog--icon-type-inner">
					<select id="starterblog--sidebar-icon-type">
						<option value="all"><?php _e( 'All Icon Types', 'starter-blog' ); ?></option>
					</select>
				</div>
			</div>
			<div class="starterblog--sidebar-search">
				<input type="text" id="starterblog--icon-search" placeholder="<?php esc_attr_e( 'Type icon name', 'starter-blog' ); ?>">
			</div>
			<div id="starterblog--icon-browser"></div>
		</div>
		<?php
	}
}
