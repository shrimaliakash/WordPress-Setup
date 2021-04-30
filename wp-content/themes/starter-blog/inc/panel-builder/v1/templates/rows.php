<script type="text/html" id="tmpl-starterblog--cb-panel">
	<div class="starterblog--cp-rows">

		<# if ( ! _.isUndefined( data.rows.top ) ) { #>
		<div class="starterblog--row-top starterblog--cb-row" data-id="{{ data.id }}_top">
			<a class="starterblog--cb-row-settings" title="{{ data.rows.top }}" data-id="top" href="#"></a>
			<div class="starterblog--row-inner">
				<div class="row--grid">
					<?php
					for ( $i = 1; $i <= 12; $i ++ ) {
						echo '<div></div>';
					}
					?>
				</div>
				<div class="starterblog--cb-items grid-stack gridster" data-id="top"></div>
			</div>
		</div>
		<# } #>

		<# if ( ! _.isUndefined( data.rows.main ) ) { #>
		<div class="starterblog--row-main starterblog--cb-row" data-id="{{ data.id }}_main">
			<a class="starterblog--cb-row-settings" title="{{ data.rows.main }}" data-id="main" href="#"></a>

			<div class="starterblog--row-inner">
				<div class="row--grid">
					<?php
					for ( $i = 1; $i <= 12; $i ++ ) {
						echo '<div></div>';
					}
					?>
				</div>
				<div class="starterblog--cb-items grid-stack gridster" data-id="main"></div>
			</div>
		</div>
		<# } #>


		<# if ( ! _.isUndefined( data.rows.bottom ) ) { #>
		<div class="starterblog--row-bottom starterblog--cb-row" data-id="{{ data.id }}_bottom">
			<a class="starterblog--cb-row-settings" title="{{ data.rows.bottom }}" data-id="bottom" href="#"></a>
			<div class="starterblog--row-inner">
				<div class="row--grid">
					<?php
					for ( $i = 1; $i <= 12; $i ++ ) {
						echo '<div></div>';
					}
					?>
				</div>
				<div class="starterblog--cb-items grid-stack gridster" data-id="bottom"></div>
			</div>
		</div>
		<# } #>
	</div>


	<# if ( data.device != 'desktop' ) { #>
		<# if ( ! _.isUndefined( data.rows.sidebar ) ) { #>
		<div class="starterblog--cp-sidebar">
			<div class="starterblog--row-bottom starterblog--cb-row" data-id="{{ data.id }}_sidebar">
				<a class="starterblog--cb-row-settings" title="{{ data.rows.sidebar }}" data-id="sidebar" href="#"></a>
				<div class="starterblog--row-inner">
					<div class="starterblog--cb-items starterblog--sidebar-items" data-id="sidebar"></div>
				</div>
			</div>
			<div>
		<# } #>
	<# } #>

</script>
