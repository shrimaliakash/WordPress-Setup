<?php

class StarterBlog_Customizer_Control_Repeater extends StarterBlog_Customizer_Control_Base {
	static function field_template() {
		?>
		<script type="text/html" id="tmpl-field-starterblog-repeater">
			<?php
			self::before_field();
			?>
			<?php echo self::field_header(); ?>
			<div class="starterblog-field-settings-inner">
			</div>
			<?php
			self::after_field();
			?>
		</script>
		<script type="text/html" id="tmpl-customize-control-repeater-item">
			<div class="starterblog--repeater-item">
				<div class="starterblog--repeater-item-heading">
					<label class="starterblog--repeater-visible" title="<?php esc_attr_e( 'Toggle item visible', 'starter-blog' ); ?>">
						<input type="checkbox" class="r-visible-input">
						<span class="r-visible-icon"></span>
						<span class="screen-reader-text"><?php _e( 'Show', 'starter-blog' ); ?></label>
					<span class="starterblog--repeater-live-title"></span>
					<div class="starterblog-nav-reorder">
						<span class="starterblog--down" tabindex="-1">
							<span class="screen-reader-text"><?php _e( 'Move Down', 'starter-blog' ); ?></span></span>
						<span class="starterblog--up" tabindex="0">
							<span class="screen-reader-text"><?php _e( 'Move Up', 'starter-blog' ); ?></span>
						</span>
					</div>
					<a href="#" class="starterblog--repeater-item-toggle">
						<span class="screen-reader-text"><?php _e( 'Close', 'starter-blog' ); ?></span></a>
				</div>
				<div class="starterblog--repeater-item-settings">
					<div class="starterblog--repeater-item-inside">
						<div class="starterblog--repeater-item-inner"></div>
						<# if ( data.addable ){ #>
						<a href="#" class="starterblog--remove"><?php _e( 'Remove', 'starter-blog' ); ?></a>
						<# } #>
					</div>
				</div>
			</div>
		</script>
		<script type="text/html" id="tmpl-customize-control-repeater-inner">
			<div class="starterblog--repeater-inner">
				<div class="starterblog--settings-fields starterblog--repeater-items"></div>
				<div class="starterblog--repeater-actions">
				<a href="#" class="starterblog--repeater-reorder"
				data-text="<?php esc_attr_e( 'Reorder', 'starter-blog' ); ?>"
				data-done="<?php _e( 'Done', 'starter-blog' ); ?>"><?php _e( 'Reorder', 'starter-blog' ); ?></a>
					<# if ( data.addable ){ #>
					<button type="button"
							class="button starterblog--repeater-add-new"><?php _e( 'Add an item', 'starter-blog' ); ?></button>
					<# } #>
				</div>
			</div>
		</script>
		<?php
	}
}
