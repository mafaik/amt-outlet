<!-- BEGIN PAGE CONTENT INNER -->
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN SAMPLE FORM PORTLET-->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-cogs font-grey-gallery"></i>
								<span class="caption-subject font-grey-gallery bold uppercase">{$title}</span>
							</div>
							<div class="tools">
								<a href="javascript:;" class="collapse">
								</a>
							</div>
						</div>
						<div class="portlet-body form">

							<form class="form-horizontal form-js" role="form" data-url="{$api_url}" data-callback="{base_url('issue')}" enctype="multipart/form-data">
								<div class="form-body">
									<input type="hidden" name="outlet_id" value="{$this->session->userdata('outlet_id')}" />
									<input type="hidden" name="token" value="{$token}" />
									<div class="form-group">
										<label class="col-md-3 control-label">Perihal *</label>
										<div class="col-md-4">
											<input type="text" class="form-control" name="subject" value="{$detail->subject}" placeholder="Enter text">
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Keterangan *</label>
										<div class="col-md-3">
											<textarea class="form-control" name="issue" rows="5">{$detail->issue}</textarea>
										</div>
									</div>
								
									
									<div class="form-group">
									<label class="col-md-3 control-label">Attachment *</label>
										<div class="col-md-3">
											<span class="btn default btn-file">
											    Select <input type="file" name="filename[]" class="files" multiple> 
											</span>
											 <label class="total-file">
										    </label>

											<div class="list-file list-group help-block">
											</div>
										</div>
									</div>
									

									

								</div>
									
								<div class="form-actions">
									<div class="row">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn grey-gallery">Submit</button>
											<a href="{base_url('issue')}" class="btn default">Cancel</a>
										</div>
									</div>
								</div>
							</form>

						</div>
					</div>
					<!-- END SAMPLE FORM PORTLET-->
					<div id="modal-ajax" class="modal container modal-scroll" tabindex="-1" aria-hidden="true"  data-height="480">
					</div>

				</div>
			</div>
			<!-- END PAGE CONTENT INNER -->