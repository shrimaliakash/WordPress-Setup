<?php
class StarterBlog_Customizer_Control_Modal extends StarterBlog_Customizer_Control_Base {
	static function field_template() {
		echo '<script type="text/html" id="tmpl-field-starterblog-modal">';
		self::before_field();
		?>
		<?php echo self::field_header(); ?>
		<div class="starterblog-actions">
			<a href="#" title="<?php esc_attr_e( 'Reset to default', 'starter-blog' ); ?>" class="action--reset" data-control="{{ field.name }}"><span class="dashicons dashicons-image-rotate"></span></a>
			<a href="#" title="<?php esc_attr_e( 'Toggle edit panel', 'starter-blog' ); ?>" class="action--edit" data-control="{{ field.name }}"><span class="dashicons dashicons-edit"></span></a>
		</div>
		<div class="starterblog-field-settings-inner">
			<input type="hidden" class="starterblog-hidden-modal-input starterblog-only" data-name="{{ field.name }}" value="{{ JSON.stringify( field.value ) }}" data-default="{{ JSON.stringify( field.default ) }}">
		</div>
		<?php
		self::after_field();
		echo '</script>';
		?>
		<script type="text/html" id="tmpl-starterblog-modal-settings">
			<div class="starterblog-modal-settings">
				<div class="starterblog-modal-settings--inner">
					<div class="starterblog-modal-settings--fields"></div>
				</div>
			</div>
		</script>
		<?php
	}
}
