<div ng-controller="data as showCase">
	<div class="box box-default">
		<div class="box-header with-border">
			<h4 class="box-title">{*label*}</h4>
		</div>
		<div id="panelbox-content" class="box-body">
			<div class="row">
				<form id="{*id*}" class="form-view" action="" method="post" enctype="multipart/form-data" data-success="" data-toastr-position="top-right">
					<fieldset>
						{*content_panelbox*}
					</fieldset>
				</form>
			</div>
		</div><!-- /.box-body -->
	</div><!-- /.box -->
</div>