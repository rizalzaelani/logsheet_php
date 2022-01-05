<div class="c-sidebar c-sidebar-light c-sidebar-fixed c-sidebar-lg-show fade-in" id="sidebar">
    <div class="c-sidebar-brand d-md-down-none m-1">
        <img src="<?= get_cookie("appLogoLight") ?? base_url('/img/logo-act.png') ?>" height="40" width="162" class="c-sidebar-brand-full">
        <img src="<?= get_cookie("appLogoDark") ?? base_url('/img/logo-act-dark.png') ?>" height="40" width="162" class="c-sidebar-brand-full-dark">
        <img class="c-sidebar-brand-minimized" src="<?= get_cookie("appLogoIcon") ?? base_url('/img/logo-act-min.png') ?>" width="40" height="38" alt="">
    </div>
    <ul class="c-sidebar-nav ps ps--active-y">
        <?php if (checkRoleList("DASHBOARD.VIEW")) : ?>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link active" href="<?= base_url('/Dashboard') ?>">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-speedometer') ?>"></use>
                    </svg> Dashboard
                </a>
            </li>
        <?php endif; ?>

        <?php if (checkRoleList("TRX.VIEW")) : ?>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link" href="<?= base_url('/Transaction') ?>">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-library-books') ?>"></use>
                    </svg> Transaction
                </a>
            </li>
        <?php endif; ?>

        <?php if (checkRoleList("FINDING.VIEW")) : ?>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link" href="<?= base_url('/Finding') ?>">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-warning') ?>"></use>
                    </svg> Finding
                </a>
            </li>
        <?php endif; ?>

        <?php if (checkRoleList("REPORT.ASSET.VIEW,REPORT.RAWDATA.VIEW")) : ?>
            <li class="c-sidebar-nav-title">REPORTING</li>

            <?php if (checkRoleList("REPORT.ASSET.VIEW")) : ?>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="<?= base_url('/ReportingAsset') ?>">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-library-bookmark') ?>"></use>
                        </svg> Asset
                    </a>
                </li>
            <?php endif; ?>

            <?php if (checkRoleList("REPORT.RAWDATA.VIEW")) : ?>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="<?= base_url('/Report') ?>">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-report') ?>"></use>
                        </svg> Report
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (checkRoleList("MASTER.ASSET.VIEW,MASTER.TAGLOCATION.VIEW,MASTER.TAG.VIEW")) : ?>
            <li class="c-sidebar-nav-title">MASTER DATA</li>

            <?php if (checkRoleList("MASTER.ASSET.VIEW")) : ?>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="<?= base_url('/Asset'); ?>">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-list-rich') ?>"></use>
                        </svg> Asset
                    </a>
                </li>
            <?php endif; ?>

            <?php if (checkRoleList("MASTER.TAGLOCATION.VIEW")) : ?>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="<?= base_url('/Location') ?>">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-location-pin') ?>"></use>
                        </svg> Location
                    </a>
                </li>
            <?php endif; ?>

            <?php if (checkRoleList("MASTER.TAG.VIEW")) : ?>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="<?= base_url('/Tag') ?>">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-tags') ?>"></use>
                        </svg> Tag
                    </a>
                </li>
            <?php endif; ?>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link" href="<?= base_url('/Template') ?>">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-file-xls') ?>"></use>
                    </svg> Template
                </a>
            </li>
        <?php endif; ?>

        <?php if (checkRoleList("NOTIFICATION.VIEW,APPLICATION.VIEW,VERSIONAPPS.VIEW,SCHEDULE.VIEW")) : ?>
            <li class="c-sidebar-nav-title">SETTING</li>

            <?php //if (checkRoleList("ACCOUNT.VIEW")) : ?>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="<?= base_url('/Account') ?>">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-user') ?>"></use>
                        </svg> Account
                    </a>
                </li>
            <?php //endif; ?>

            <?php if (checkRoleList("NOTIFICATION.VIEW")) : ?>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="<?= base_url('/Notification') ?>">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-bell-ring') ?>"></use>
                        </svg> Notification
                    </a>
                </li>
            <?php endif; ?>

            <?php if (checkRoleList("APPLICATION.VIEW")) : ?>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="<?= base_url('/Application') ?>">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-list-numbered') ?>"></use>
                        </svg> Application
                    </a>
                </li>
            <?php endif; ?>

            <?php if (checkRoleList("VERSIONAPPS.VIEW")) : ?>
                <li class="c-sidebar-nav-item d-none">
                    <a class="c-sidebar-nav-link" href="<?= base_url('/VersionApps') ?>">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-paper-plane') ?>"></use>
                        </svg> Version Apps
                    </a>
                </li>
            <?php endif; ?>

            <?php if (checkRoleList("SCHEDULE.VIEW")) : ?>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="<?= base_url('/Schedule') ?>">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-calendar') ?>"></use>
                        </svg> Schedule
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (checkRoleList("USER.VIEW,ROLE.VIEW")) : ?>
            <li class="c-sidebar-nav-title">USER & ROLE</li>

            <?php if (checkRoleList("USER.VIEW")) : ?>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="<?= base_url('/user') ?>">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="<?= base_url('/icons/coreui/svg/free.svg#cil-user') ?>"></use>
                        </svg> User
                    </a>
                </li>
            <?php endif; ?>

            <?php if (checkRoleList("ROLE.VIEW")) : ?>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="<?= base_url('/role') ?>">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="<?= base_url('/icons/coreui/svg/free.svg#cil-lock-locked') ?>"></use>
                        </svg> Role
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (checkRoleList("LOGACTIVITY.VIEW")) : ?>
            <li class="c-sidebar-nav-title">LOG ACTIVTY</li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link" href="<?= base_url('/LogActivity') ?>">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="<?= base_url('/icons/coreui/svg/solid.svg#cis-history') ?>"></use>
                    </svg> Log Activity
                </a>
            </li>
        <?php endif; ?>
        <li class="c-sidebar-nav-title">CUSTOMERS</li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="<?= base_url('/Subscription') ?>">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-cc') ?>"></use>
                </svg> Subscription
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="<?= base_url('/CustomersTransaction') ?>">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-excerpt') ?>"></use>
                </svg> Transaction
            </a>
        </li>

        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
        </div>
        <div class="ps__rail-y" style="top: 0px; height: 514px; right: 0px;">
            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
        </div>
    </ul>
    <div class="c-class-toggler d-flex justify-content-between align-items-center" type="button" data-target="_parent" data-class="c-sidebar-unfoldable">

        <!-- <img class="p-2 ml-2 mr-2 c-sidebar-brand-full" src="/img/logo2.svg" alt="" height="49" width="200"> -->

        <!-- <img class="p-2 ml-3 c-sidebar-brand-full" src="/img/nocola-logo.png" alt="" width="180"> -->

        <img class="p-2 ml-3 c-sidebar-brand-full" src="<?= base_url('/img/nocola-logo.png') ?>" alt="" width="180">
        <img class="p-2 ml-3 c-sidebar-brand-full-dark" src="<?= base_url('/img/nocola-logo-dark.png') ?>" alt="" width="180">

        <button class="c-sidebar-minimizer"></button>
    </div>
</div>