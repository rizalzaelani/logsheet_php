<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row">
	<div class="col-12">
		<div class="card card-main card-border-top">
			<div class="container-fluid">
				<div class="card-header d-flex flex-row justify-content-between">
					<h4 class="title"><?= (isset($subtitle) ? $subtitle : ''); ?></h4>
					<div>
						<div class="" id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; width: 100%">
							<i class="fa fa-calendar"></i>&nbsp;
							<span></span><i class="fa fa-caret-down"></i>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-4">
							<div class="border-light">
								<ul class="list-group list-group-flush">
									<li class="list-group-item">First item</li>
									<li class="list-group-item">Second item</li>
									<li class="list-group-item">Third item</li>
									<li class="list-group-item">Fourth item</li>
								</ul>
							</div>
						</div>
						<div class="col-8 border-light">
							<form>
								<div class="mt-2">
									<input type="search" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Search Company">
								</div>
								<hr>
								<div class="my-4">
									<a href="#" class="btn btn-outline-light text-dark" style="width: 150px;">Example</a>
								</div>
								<hr>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<div class="row">
			<div class="col-4">
				<div class="card card-main card-border-top">
					<div class="card-body">
						<h1>Hello</h1>
					</div>
				</div>
			</div>
			<div class="col-5">
				<div class="card card-main card-border-top">
					<div class="card-body">
						<h1>World</h1>
					</div>
				</div>
			</div>
			<div class="col-3">
				<div class="card card-main card-border-top">
					<div class="card-body">
						<h1>!</h1>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-5">
				<div class="card card-main card-border-top">
					<div class="card-body">

					</div>
				</div>
			</div>
			<div class="col-7">
				<div class="card card-main card-border-top">
					<div class="card-body">

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