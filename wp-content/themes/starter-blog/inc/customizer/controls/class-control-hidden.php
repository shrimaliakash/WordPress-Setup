<?php
class StarterBlog_Customizer_Control_Hidden extends StarterBlog_Customizer_Control_Base {
	static function field_template() {
		?>
		<script type="text/html" id="tmpl-field-starterblog-hidden">
		<?php
		self::before_field();
		?>
		<input type="hidden" class="starterblog-input starterblog-only" data-name="{{ field.name }}" value="{{ field.value }}">
		<?php
		self::after_field();
		?>
		</script>
		<?php
	}
}
