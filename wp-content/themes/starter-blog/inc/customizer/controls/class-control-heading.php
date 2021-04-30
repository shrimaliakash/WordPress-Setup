<?php
class StarterBlog_Customizer_Control_Heading extends StarterBlog_Customizer_Control_Base {
	static function field_template() {
		?>
		<script type="text/html" id="tmpl-field-starterblog-heading">
		<?php
		self::before_field();
		?>
		<h3 class="starterblog-field--heading">{{ field.label }}</h3>
		<?php
		self::after_field();
		?>
		</script>
		<?php
	}
}
