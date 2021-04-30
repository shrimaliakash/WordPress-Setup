<?php
class StarterBlog_Customizer_Control_Shadow extends StarterBlog_Customizer_Control_Base {
	static function field_template() {
		echo '<script type="text/html" id="tmpl-field-starterblog-shadow">';
		self::before_field();
		?>
		<#
			if ( ! _.isObject( field.value ) ) {
			field.value = { };
			}

			var uniqueID = field.name + ( new Date().getTime() );
		#>
			<?php echo self::field_header(); ?>
			<div class="starterblog-field-settings-inner">

				<div class="starterblog-input-color" data-default="{{ field.default }}">
					<input type="hidden" class="starterblog-input starterblog-input--color" data-name="{{ field.name }}-color" value="{{ field.value.color }}">
					<input type="text" class="starterblog--color-panel" data-alpha="true" value="{{ field.value.color }}">
				</div>

				<div class="starterblog--gr-inputs">
					<span>
						<input type="number" class="starterblog-input starterblog-input-css change-by-js"  data-name="{{ field.name }}-x" value="{{ field.value.x }}">
						<span class="starterblog--small-label"><?php _e( 'X', 'starter-blog' ); ?></span>
					</span>
					<span>
						<input type="number" class="starterblog-input starterblog-input-css change-by-js"  data-name="{{ field.name }}-y" value="{{ field.value.y }}">
						<span class="starterblog--small-label"><?php _e( 'Y', 'starter-blog' ); ?></span>
					</span>
					<span>
						<input type="number" class="starterblog-input starterblog-input-css change-by-js" data-name="{{ field.name }}-blur" value="{{ field.value.blur }}">
						<span class="starterblog--small-label"><?php _e( 'Blur', 'starter-blog' ); ?></span>
					</span>
					<span>
						<input type="number" class="starterblog-input starterblog-input-css change-by-js" data-name="{{ field.name }}-spread" value="{{ field.value.spread }}">
						<span class="starterblog--small-label"><?php _e( 'Spread', 'starter-blog' ); ?></span>
					</span>
					<span>
						<span class="input">
							<input type="checkbox" class="starterblog-input starterblog-input-css change-by-js" <# if ( field.value.inset == 1 ){ #> checked="checked" <# } #> data-name="{{ field.name }}-inset" value="{{ field.value.inset }}">
						</span>
						<span class="starterblog--small-label"><?php _e( 'inset', 'starter-blog' ); ?></span>
					</span>
				</div>
			</div>
			<?php
			self::after_field();
			echo '</script>';
	}
}
