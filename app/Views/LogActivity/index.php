<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row">
	<div class="col-12">
		<div class="card card-main card-border-top fixed-height">
			<div class="container-fluid">
				<div class="card-header d-flex flex-row justify-content-between">
					<h4 class="title"><?= (isset($subtitle) ? $subtitle : ''); ?></h4>
					<div>
						<div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; width: 100%">
							<i class="fa fa-calendar"></i>&nbsp;
							<span></span> <i class="fa fa-caret-down"></i>
						</div>
					</div>
				</div>
				<div class="card-body">
					<!-- datatable -->
					<div class="row">
						<div class="col-12">
							<table class="table table-hover table-striped" id="example">
								<thead class="bg-primary">
									<tr>
										<th>No</th>
										<th>Username</th>
										<th>IP Address</th>
										<th>Activity</th>
										<th>Date</th>
										<th>Action</th>
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
<?= $this->endSection(); ?>

<?= $this->section('customScripts'); ?>
<!-- Custom Script Js -->
<?= $this->endSection(); ?>