<?php
class StarterBlog_Customizer_Control_Textarea extends StarterBlog_Customizer_Control_Base {
	static function field_template() {
		echo '<script type="text/html" id="tmpl-field-starterblog-textarea">';
		self::before_field();
		?>
		<?php echo self::field_header(); ?>
		<div class="starterblog-field-settings-inner">
			<textarea rows="10" class="starterblog-input" data-name="{{ field.name }}">{{ field.value }}</textarea>
		</div>
		<?php
		self::after_field();
		echo '</script>';
	}
}
