<?php
class StarterBlog_Customizer_Control_Color extends StarterBlog_Customizer_Control_Base {
	static function field_template() {
		?>
		<script type="text/html" id="tmpl-field-starterblog-color">
		<?php
		self::before_field();
		?>
		<?php echo self::field_header(); ?>
		<div class="starterblog-field-settings-inner">
			<div class="starterblog-input-color" data-default="{{ field.default }}">
				<input type="hidden" class="starterblog-input starterblog-input--color" data-name="{{ field.name }}" value="{{ field.value }}">
				<input type="text" class="starterblog--color-panel" placeholder="{{ field.placeholder }}" data-alpha="true" value="{{ field.value }}">
			</div>
		</div>
		<?php
		self::after_field();
		?>
		</script>
		<?php
	}
}
