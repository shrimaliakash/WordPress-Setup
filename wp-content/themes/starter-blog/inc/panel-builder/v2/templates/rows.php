<script type="text/html" id="tmpl-starterblog--cb-panel-v2">
	<div class="cp-rows-v2 starterblog--cp-rows">

		<# if ( ! _.isUndefined( data.rows.top ) ) { #>
		<div class="starterblog--row-top starterblog--cb-row" data-row-id="top" data-id="{{ data.id }}_top">
			<a class="starterblog--cb-row-settings" title="{{ data.rows.top }}" data-id="top" href="#"></a>
			<div class="starterblog--row-inner">

				<div class="col-items-wrapper"><div data-id="left" class="col-items col-left"></div></div>
				<div class="col-items-wrapper"><div data-id="center" class="col-items col-center"></div></div>
				<div class="col-items-wrapper"><div data-id="right" class="col-items col-right"></div></div>

			</div>
		</div>
		<# } #>

		<# if ( ! _.isUndefined( data.rows.main ) ) { #>
		<div class="starterblog--row-main starterblog--cb-row" data-row-id="main" data-id="{{ data.id }}_main">
			<a class="starterblog--cb-row-settings" title="{{ data.rows.main }}" data-id="main" href="#"></a>

			<div class="starterblog--row-inner">
				
				<div class="col-items-wrapper"><div data-id="left" class="col-items col-left"></div></div>
				<div class="col-items-wrapper"><div data-id="center" class="col-items col-center"></div></div>
				<div class="col-items-wrapper"><div data-id="right" class="col-items col-right"></div></div>
				
			</div>
		</div>
		<# } #>

		<# if ( ! _.isUndefined( data.rows.bottom ) ) { #>
		<div class="starterblog--row-bottom starterblog--cb-row" data-row-id="bottom" data-id="{{ data.id }}_bottom">
			<a class="starterblog--cb-row-settings" title="{{ data.rows.bottom }}" data-id="bottom" href="#"></a>
			<div class="starterblog--row-inner">

				<div class="col-items-wrapper"><div data-id="left" class="col-items col-left"></div></div>
				<div class="col-items-wrapper"><div data-id="center" class="col-items col-center"></div></div>
				<div class="col-items-wrapper"><div data-id="right" class="col-items col-right"></div></div>

			</div>
		</div>
		<# } #>
	</div>


	<# if ( data.device != 'desktop' ) { #>
		<# if ( ! _.isUndefined( data.rows.sidebar ) ) { #>
		<div class="starterblog--cp-sidebar">
			<div class="starterblog--row-sidebar starterblog--cb-row" data-row-id="sidebar" data-id="{{ data.id }}_sidebar">
				<a class="starterblog--cb-row-settings" title="{{ data.rows.sidebar }}" data-id="sidebar" href="#"></a>
				<div class="starterblog--row-inner">

					<div class="col-items-wrapper"><div data-id="sidebar" class="col-items col-sidebar"></div></div>

				</div>
			</div>
			<div>
		<# } #>
	<# } #>

</script>