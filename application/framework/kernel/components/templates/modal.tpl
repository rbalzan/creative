<input id="id" type="hidden" value="-1"/>

<div id="modal_wiewrecord" class="modal fade" role="dialog" aria-labelledby="modal_wiewrecord_title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-{size}">
		<div class="modal-content">
			{header}
			<form id="modal_wiewrecord_form" class="form-view" action="" method="post" enctype="multipart/form-data" data-success="" data-toastr-position="top-right">
				<fieldset>							
					<!-- body modal -->
					<div class="modal-body">
						<div class="row">
							{body}
						</div>
					</div>

					<!-- Modal Footer -->
					<div class="modal-footer">
						<button class="btn btn-danger pull-left" data-dismiss="modal"><span class="fa fa-ban"></span> Cerrar</button>
						<button id="modal_wiewrecord_submit" type="submit" class="btn btn-success"><span class="fa fa-check"></span> Aceptar</button>
					</div>
				</fieldset>
			</form>
			
		</div>
	</div>
</div>