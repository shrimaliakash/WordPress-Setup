<?php
class StarterBlog_Customizer_Control_Text_Align_No_Justify extends StarterBlog_Customizer_Control_Base {
	static function field_template() {

		echo '<script type="text/html" id="tmpl-field-starterblog-text_align_no_justify">';
		self::before_field();
		?>
		<#
		if ( _.isUndefined( field.no_justify ) )  {
				field.no_justify = true;
		}
		field.no_justify = true;

		var uniqueID = field.name + ( new Date().getTime() );
		#>
		<?php echo self::field_header(); ?>
		<div class="starterblog-field-settings-inner">
			<div class="starterblog-text-align">
				<label><input type="radio" data-name="{{ field.name }}" value="left" <# if ( field.value == 'left' ){ #> checked="checked" <# } #> name="{{ uniqueID }}"> <span class="button"><span class="dashicons dashicons-editor-alignleft"></span></span></label>
				<label><input type="radio" data-name="{{ field.name }}" value="center" <# if ( field.value == 'center' ){ #> checked="checked" <# } #> name="{{ uniqueID }}"> <span class="button"><span class="dashicons dashicons-editor-aligncenter"></span></span></label>
				<label><input type="radio" data-name="{{ field.name }}" value="right" <# if ( field.value == 'right' ){ #> checked="checked" <# } #> name="{{ uniqueID }}"> <span class="button"><span class="dashicons dashicons-editor-alignright"></span></span></label>
				<# if ( ! field.no_justify ) {  #>
				<label><input type="radio" data-name="{{ field.name }}" value="justify" <# if ( field.value == 'justify' ){ #> checked="checked" <# } #> name="{{ uniqueID }}"> <span class="button"><span class="dashicons dashicons-editor-justify"></span></span></label>
				<# } #>
			</div>
		</div>
		<?php
		self::after_field();
		echo '</script>';

	}
}
