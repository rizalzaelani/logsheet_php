<div class="c-sidebar c-sidebar-light c-sidebar-fixed c-sidebar-lg-show fade-in" id="sidebar">
    <div class="c-sidebar-brand d-md-down-none m-1">
        <img src="/img/logo-act.png" height="40" width="162" class="c-sidebar-brand-full">
        <img class="c-sidebar-brand-minimized" src="/img/logo-act-min.png" width="40" height="38" alt="">
    </div>
    <ul class="c-sidebar-nav ps ps--active-y">
        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link active" href="<?= base_url('/Dashboard') ?>">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-speedometer') ?>"></use>
                </svg> Dashboard</a>
        </li>
        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url('/Transaction') ?>">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-library-books') ?>"></use>
                </svg> Transaction</a>
        </li>
        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url('/Finding') ?>">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="<?= base_url('/icons/coreui/svg/solid.svg#cis-find-replace') ?>"></use>
                </svg> Finding</a>
        </li>
        <!-- <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url('/IncidentalReport') ?>">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-book-open') ?>"></use>
                </svg> Incidental Report</a>
        </li> -->
        <li class="c-sidebar-nav-title">REPORTING</li>
        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url('/Equipment') ?>">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-library-bookmark') ?>"></use>
                </svg> Asset</a>
        </li>

        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url('/Report') ?>">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-report') ?>"></use>
                </svg> Report</a>
        </li>
        <li class="c-sidebar-nav-title">MASTER DATA</li>
        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url('/Asset'); ?>">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-list-rich') ?>"></use>
                </svg> Asset</a>
        </li>
        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url('/Location') ?>">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-location-pin') ?>"></use>
                </svg> Location</a>
        </li>
        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url('/Tag') ?>">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-tags') ?>"></use>
                </svg> Tag</a>
        </li>
        <!-- <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url('/Operation') ?>">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="<?= base_url('/icons/coreui/svg/free.svg#cil-settings') ?>"></use>
                </svg> Operation</a>
        </li> -->
        <li class="c-sidebar-nav-title">SETTING</li>
        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url('/Notification') ?>">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-bell-ring') ?>"></use>
                </svg> Notification</a>
        </li>
        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url('/Application') ?>">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-list-numbered') ?>"></use>
                </svg> Application</a>
        </li>
        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url('/VersionApps') ?>">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-paper-plane') ?>"></use>
                </svg> Version Apps</a>
        </li>
        <li class="c-sidebar-nav-title">LOG ACTIVTY</li>
        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url('/LogActivity') ?>">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="<?= base_url('/icons/coreui/svg/solid.svg#cis-history') ?>"></use>
                </svg> Log Activity</a>
        </li>

        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
        </div>
        <div class="ps__rail-y" style="top: 0px; height: 514px; right: 0px;">
            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
        </div>
    </ul>
    <div class="c-class-toggler d-flex justify-content-between align-items-center" type="button" data-target="_parent" data-class="c-sidebar-unfoldable">
        <img class="p-2 ml-3 c-sidebar-brand-full" src="/img/nocola-logo.png" alt="" width="180">
        <button class="c-sidebar-minimizer"></button>
    </div>
</div>