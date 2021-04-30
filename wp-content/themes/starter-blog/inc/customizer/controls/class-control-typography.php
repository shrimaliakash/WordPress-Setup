<?php
class StarterBlog_Customizer_Control_Typography extends StarterBlog_Customizer_Control_Base {
	static function field_template() {
		echo '<script type="text/html" id="tmpl-field-starterblog-typography">';
		self::before_field();
		?>
		<?php echo self::field_header(); ?>
		<div class="starterblog-actions">
			<a href="#" class="action--reset" data-control="{{ field.name }}" title="<?php esc_attr_e( 'Reset to default', 'starter-blog' ); ?>"><span class="dashicons dashicons-image-rotate"></span></a>
			<a href="#" class="action--edit" data-control="{{ field.name }}" title="<?php esc_attr_e( 'Toggle edit panel', 'starter-blog' ); ?>"><span class="dashicons dashicons-edit"></span></a>
		</div>
		<div class="starterblog-field-settings-inner">
			<input type="hidden" class="starterblog-typography-input starterblog-only" data-name="{{ field.name }}" value="{{ JSON.stringify( field.value ) }}" data-default="{{ JSON.stringify( field.default ) }}">
		</div>
		<?php
		self::after_field();
		echo '</script>';
		?>
		<div id="starterblog-typography-panel" class="starterblog-typography-panel">
			<div class="starterblog-typography-panel--inner">
				<input type="hidden" id="starterblog--font-type">
				<div id="starterblog-typography-panel--fields"></div>
			</div>
		</div>
		<?php
	}
}
