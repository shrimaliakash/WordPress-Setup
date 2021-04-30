<?php
class StarterBlog_Customizer_Control_Font extends StarterBlog_Customizer_Control_Base {
	static function field_template() {
		?>
		<script type="text/html" id="tmpl-field-starterblog-css-ruler">
		<?php
		self::before_field();
		?>
		<?php echo self::field_header(); ?>
		<div class="starterblog-field-settings-inner">
			<input type="hidden" class="starterblog--font-type" data-name="{{ field.name }}-type" >
			<div class="starterblog--font-families-wrapper">
				<select class="starterblog--font-families" data-value="{{ JSON.stringify( field.value ) }}" data-name="{{ field.name }}-font"></select>
			</div>
			<div class="starterblog--font-variants-wrapper">
				<label><?php _e( 'Variants', 'starter-blog' ); ?></label>
				<select class="starterblog--font-variants" data-name="{{ field.name }}-variant"></select>
			</div>
			<div class="starterblog--font-subsets-wrapper">
				<label><?php _e( 'Languages', 'starter-blog' ); ?></label>
				<div data-name="{{ field.name }}-subsets" class="list-subsets">
				</div>
			</div>
		</div>
		<?php
		self::after_field();
		?>
		</script>
		<?php
	}
}
