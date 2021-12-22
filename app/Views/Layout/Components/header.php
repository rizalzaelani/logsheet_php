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
        <ol class="breadcrumb border-bottom-0">
            <?php foreach (($breadcrumbs ?? []) as $key => $row) { ?>
                <?php if (count($breadcrumbs ?? []) == $key + 1) { ?>
                    <li class="breadcrumb-item active text-capitalize"><?= $row['title']; ?></li>
                <?php } else { ?>
                    <li class="breadcrumb-item text-capitalize"><a href="<?= site_url($row['link']) ?>"><?= $row['title']; ?></a></li>
                <?php } ?>
            <?php } ?>
        </ol>
    </div>
    <ul class="c-header-nav mfs-auto">
        <li class="c-header-nav-item px-1 c-d-legacy-none">
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
                    <strong class="text-uppercase"><?php $session = \Config\Services::session(); echo $session->get('name') ?></strong>
                </div>
                <a class="dropdown-item" href="<?= site_url("Account") ?>">
                    <svg class="c-icon mfe-2">
                        <use xlink:href="<?= base_url('/icons/coreui/svg/free.svg#cil-user') ?>"></use>
                    </svg> Account
                </a>
                <a class="dropdown-item" href="<?= base_url('/logout'); ?>">
                    <svg class="c-icon mfe-2">
                        <use xlink:href="<?= base_url('/icons/coreui/svg/free.svg#cil-account-logout') ?>"></use>
                    </svg> Logout
                </a>
            </div>
        </li>
    </ul>
</header>