<?php
class StarterBlog_Customizer_Control_Video extends StarterBlog_Customizer_Control_Media {
	static function field_template() {
		echo '<script type="text/html" id="tmpl-field-starterblog-video">';
		self::before_field();
		?>
		<#
		if ( ! _.isObject(field.value) ) {
			field.value = {};
		}
		var url = field.value.url;
		#>
		<?php echo self::field_header(); ?>
		<div class="starterblog-field-settings-inner starterblog-media-type-{{ field.type }}">
			<div class="starterblog--media">
				<input type="hidden" class="attachment-id" value="{{ field.value.id }}" data-name="{{ field.name }}">
				<input type="hidden" class="attachment-url"  value="{{ field.value.url }}" data-name="{{ field.name }}-url">
				<input type="hidden" class="attachment-mime"  value="{{ field.value.mime }}" data-name="{{ field.name }}-mime">
				<div class="starterblog-image-preview <# if ( url ) { #> starterblog--has-file <# } #>" data-no-file-text="<?php esc_attr_e( 'No file selected', 'starter-blog' ); ?>">
					<#

					if ( url ) {
						if ( url.indexOf('http://') > -1 || url.indexOf('https://') ){

						} else {
							url = StarterBlog_Control_Args.home_url + url;
						}

						if ( ! field.value.mime || field.value.mime.indexOf('image/') > -1 ) {
							#>
							<img src="{{ url }}" alt="">
						<# } else if ( field.value.mime.indexOf('video/' ) > -1 ) { #>
							<video width="100%" height="" controls><source src="{{ url }}" type="{{ field.value.mime }}">Your browser does not support the video tag.</video>
						<# } else {
						var basename = url.replace(/^.*[\\\/]/, '');
						#>
							<a href="{{ url }}" class="attachment-file" target="_blank">{{ basename }}</a>
						<# }
					}
					#>
				</div>
				<button type="button" class="button starterblog--add <# if ( url ) { #> starterblog--hide <# } #>"><?php _e( 'Add', 'starter-blog' ); ?></button>
				<button type="button" class="button starterblog--change <# if ( ! url ) { #> starterblog--hide <# } #>"><?php _e( 'Change', 'starter-blog' ); ?></button>
				<button type="button" class="button starterblog--remove <# if ( ! url ) { #> starterblog--hide <# } #>"><?php _e( 'Remove', 'starter-blog' ); ?></button>
			</div>
		</div>

		<?php
		self::after_field();
		echo '</script>';
	}
}
