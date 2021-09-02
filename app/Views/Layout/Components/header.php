<header class="c-header c-header-light fade-in p-1">
    <div class="d-flex flex-row align-items-center">
        <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show">
            <svg class="c-icon c-icon-lg">
                <use xlink:href="<?= base_url('/icons/coreui/svg/free.svg#cil-menu') ?>"></use>
            </svg>
        </button>
        <button class="c-header-toggler c-class-toggler mfs-3 d-md-down-none" type="button" data-target="#sidebar" data-class="c-sidebar-lg-show" responsive="true">
            <svg class="c-icon c-icon-lg">
                <use xlink:href="<?= base_url('/icons/coreui/svg/free.svg#cil-menu') ?>"></use>
            </svg>
        </button>
        <!-- <h6 class="text-uppercase mb-0"><?= $title ?></h6> -->
        <ol class="breadcrumb m-0 border-bottom-0">
            <?php foreach (($breadcrumbs ?? []) as $key => $row) { ?>
                <?php if (count($breadcrumbs ?? []) == $key + 1) { ?>
                    <li class="breadcrumb-item active text-capitalize"><?= $row; ?></li>
                <?php } else { ?>
                    <li class="breadcrumb-item text-capitalize"><a href="<?= site_url($row) ?>"><?= $row; ?></a></li>
                <?php } ?>
            <?php } ?>
        </ol>
    </div>
    <ul class="c-header-nav mfs-auto">
        <li class="c-header-nav-item px-3 c-d-legacy-none">
            <button class="c-class-toggler c-header-nav-btn" type="button" id="header-tooltip" data-target="body" data-class="c-dark-theme" data-toggle="c-tooltip" data-placement="bottom" title="Toggle Light/Dark Mode">
                <svg class="c-icon c-d-dark-none">
                    <use xlink:href="<?= base_url('/icons/coreui/svg/free.svg#cil-moon') ?>"></use>
                </svg>
                <svg class="c-icon c-d-default-none">
                    <use xlink:href="<?= base_url('/icons/coreui/svg/free.svg#cil-sun') ?>"></use>
                </svg>
            </button>
        </li>
    </ul>
    <ul class="c-header-nav">
        <li class="c-header-nav-item dropdown">
            <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <div class="c-avatar">
                    <!-- <img class="c-avatar-img" src="<?= base_url('/img/avatars/6.jpg') ?>" alt="user@email.com"> -->
                    <i class="c-avatar-img fa fa-user"></i>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right pt-0 mr-2">
                <div class="dropdown-header bg-light py-2">
                    <strong>Account</strong>
                </div>
                <a class="dropdown-item" href="#">
                    <svg class="c-icon mfe-2">
                        <use xlink:href="<?= base_url('/icons/coreui/svg/free.svg#cil-bell') ?>"></use>
                    </svg> Updates<span class="badge badge-info mfs-auto">42</span>
                </a>
                <a class="dropdown-item" href="#">
                    <svg class="c-icon mfe-2">
                        <use xlink:href="<?= base_url('/icons/coreui/svg/free.svg#cil-envelope-open') ?>"></use>
                    </svg> Messages<span class="badge badge-success mfs-auto">42</span>
                </a>
                <a class="dropdown-item" href="#">
                    <svg class="c-icon mfe-2">
                        <use xlink:href="<?= base_url('/icons/coreui/svg/free.svg#cil-task') ?>"></use>
                    </svg> Tasks<span class="badge badge-danger mfs-auto">42</span>
                </a>
                <a class="dropdown-item" href="#">
                    <svg class="c-icon mfe-2">
                        <use xlink:href="<?= base_url('/icons/coreui/svg/free.svg#cil-comment-square') ?>"></use>
                    </svg> Comments<span class="badge badge-warning mfs-auto">42</span>
                </a>
                <div class="dropdown-header bg-light py-2">
                    <strong>Settings</strong>
                </div>
                <a class="dropdown-item" href="#">
                    <svg class="c-icon mfe-2">
                        <use xlink:href="<?= base_url('/icons/coreui/svg/free.svg#cil-user') ?>"></use>
                    </svg> Profile
                </a>
                <a class="dropdown-item" href="#">
                    <svg class="c-icon mfe-2">
                        <use xlink:href="<?= base_url('/icons/coreui/svg/free.svg#cil-settings') ?>"></use>
                    </svg> Settings
                </a>
                <a class="dropdown-item" href="#">
                    <svg class="c-icon mfe-2">
                        <use xlink:href="<?= base_url('/icons/coreui/svg/free.svg#cil-credit-card') ?>"></use>
                    </svg> Payments<span class="badge badge-secondary mfs-auto">42</span>
                </a>
                <a class="dropdown-item" href="#">
                    <svg class="c-icon mfe-2">
                        <use xlink:href="<?= base_url('/icons/coreui/svg/free.svg#cil-file') ?>"></use>
                    </svg> Projects<span class="badge badge-primary mfs-auto">42</span>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">
                    <svg class="c-icon mfe-2">
                        <use xlink:href="<?= base_url('/icons/coreui/svg/free.svg#cil-lock-locked') ?>"></use>
                    </svg> Lock Account
                </a>
                <a class="dropdown-item" href="#">
                    <svg class="c-icon mfe-2">
                        <use xlink:href="<?= base_url('/icons/coreui/svg/free.svg#cil-account-logout') ?>"></use>
                    </svg> Logout
                </a>
            </div>
        </li>
    </ul>
    <!-- <div class="c-subheader justify-content-between px-3">
        <ol class="breadcrumb border-0 m-0 px-0 px-md-3">
            <li class="breadcrumb-item"><a href="/Dashboard" class="" style="color: #003399">Home</a></li>
            <li class="breadcrumb-item"><?= (isset($title) ? $title : '')  ?></li>
        </ol>
        <div class="c-subheader-nav d-md-down-none mfe-2">
            <a class="c-subheader-nav-link" href="#">
                <svg class="c-icon">
                    <use xlink:href="<?= base_url('/icons/coreui/svg/free.svg#cil-settings') ?>"></use>
                </svg> &nbsp;Account Settings
            </a>
        </div>
    </div> -->
</header>