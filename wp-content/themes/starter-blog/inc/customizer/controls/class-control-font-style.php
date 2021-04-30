<?php
class StarterBlog_Customizer_Control_Font_Style extends StarterBlog_Customizer_Control_Base {
	static function field_template() {
		?>
		<script type="text/html" id="tmpl-field-starterblog-font-style">
		<?php
		self::before_field();
		?>
		<?php echo self::field_header(); ?>
		<#
		if ( ! _.isObject( field.value ) ) {
			field.value = { };
		}
		#>
		<div class="starterblog-field-settings-inner starterblog--font-style">
			<label title="<?php esc_attr_e( 'Bold', 'starter-blog' ); ?>" class="button <# if ( field.value.b == 1 ){ #> starterblog--checked <# } #>"><input type="checkbox" <# if ( field.value.b == 1 ){ #> checked="checked" <# } #> data-name="{{ field.name }}-b" value="1"><span class="dashicons dashicons-editor-bold"></span></label>
			<label title="<?php esc_attr_e( 'Italic', 'starter-blog' ); ?>" class="button <# if ( field.value.i == 1 ){ #> starterblog--checked <# } #>"><input type="checkbox" <# if ( field.value.i == 1 ){ #> checked="checked" <# } #> data-name="{{ field.name }}-i" value="1"><span class="dashicons dashicons-editor-italic"></span></label>
			<label title="<?php esc_attr_e( 'Underline', 'starter-blog' ); ?>" class="button <# if ( field.value.u == 1 ){ #> starterblog--checked <# } #>"><input type="checkbox" <# if ( field.value.u == 1 ){ #> checked="checked" <# } #> data-name="{{ field.name }}-u" value="1"><span class="dashicons dashicons-editor-underline"></span></label>
			<label title="<?php esc_attr_e( 'Strikethrough', 'starter-blog' ); ?>" class="button <# if ( field.value.s == 1 ){ #> starterblog--checked <# } #>"><input type="checkbox" <# if ( field.value.s == 1 ){ #> checked="checked" <# } #> data-name="{{ field.name }}-s" value="1"><span class="dashicons dashicons-editor-strikethrough"></span></label>
			<label title="<?php esc_attr_e( 'Uppercase', 'starter-blog' ); ?>" class="button <# if ( field.value.t == 1 ){ #> starterblog--checked <# } #>"><input type="checkbox" <# if ( field.value.t == 1 ){ #> checked="checked" <# } #> data-name="{{ field.name }}-t" value="1"><span class="dashicons dashicons-editor-textcolor"></span></label>
		</div>
		<?php
		self::after_field();
		?>
		</script>
		<?php
	}
}
