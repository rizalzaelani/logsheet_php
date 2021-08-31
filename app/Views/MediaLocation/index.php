<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row">
	<div class="col-12">
		<div class="card card-main card-border-top fixed-height">
			<div class="container-fluid">
				<div class="card-header">
					<h4 class="title"><?= (isset($subtitle) ? $subtitle : ''); ?></h4>
				</div>
				<div class="card-body">
					<div class="container">
						<div class="row">
							<div class="col-12 border-light" style="border-radius: 7px;">
								<div class="input-group my-3">
									<span class="col-form-label mr-3 col-2">Incidental Report</span>
									<input type="text" aria-label="First name" class="form-control" value="http://149.28.157.175/IPCLogsheet/" readonly="">
									<input type="text" aria-label="Last name" class="form-control" placeholder="Type Here">
									<a href="" class="btn btn-outline-info input-group-text">Check Directory</a>
								</div>
								<div class="input-group my-3">
									<span class="col-form-label mr-3 col-2">IPC Logsheet</span>
									<input type="text" aria-label="First name" class="form-control" value="http://149.28.157.175/IPCLogsheet/" readonly="">
									<input type="text" aria-label="Last name" class="form-control" placeholder="Type Here">
									<a href="" class="btn btn-outline-info input-group-text">Check Directory</a>
								</div>
								<div class="input-group my-3">
									<span class="col-form-label mr-3 col-2">Plant Patrol</span>
									<input type="text" aria-label="First name" class="form-control" value="http://149.28.157.175/IPCLogsheet/" readonly="">
									<input type="text" aria-label="Last name" class="form-control" placeholder="Type Here">
									<a href="" class="btn btn-outline-info input-group-text">Check Directory</a>
								</div>
								<div class="my-3 d-flex justify-content-end">
									<a href="" class="btn btn-sm btn-primary">Update</a>
								</div>
							</div>
						</div>
						<div class="row mt-5">
							<div class="col-12">
								<table class="table table-hover table-striped" id="example">
									<thead class="bg-primary">
										<tr>
											<th>No</th>
											<th>Record IPC Logsheet</th>
											<th>Record Plant Patrol</th>
											<th>Incidental Report</th>
											<th>Date</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('customScripts'); ?>
<!-- Custom Script Js -->
<?= $this->endSection(); ?>