<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?= base_url(); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= get_cookie("appLogoIcon") ?? base_url('/img/icon.png'); ?>" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="Core UI">
    <meta name="author" content="CoreUI">
    <meta name="keyword" content="Dashboard,Monitor,CoreUI">
    <title><?= get_cookie("appName") ?? 'Nocola - Logsheet Digital' ?></title>

    <style type="text/css">
        #changePassModal .table th,
        #changePassModal .table td {
            border-top: 0 !important;
        }
    </style>

    <!-- Main styles for this application-->
    <?php
    $session = \Config\Services::session();

    $userParam = json_decode($session->get("parameter"), true);
    $userDt = array(
        "email" => $session->get("email"),
        "role" => $session->get("group"),
        "fullname" => $userParam['fullname'],
        "company" => $userParam['company'],
        "city" => $userParam['city'],
        "country" => $userParam['country'],
        "postalCode" => $userParam['postalCode'],
        "noTelp" => $userParam['noTelp'],
        "tag" => $userParam['tag'],
        "tagLocation" => $userParam['tagLocation'],
    );

    if (isset($css)) : ?>
        <?php foreach ($css as $item) : ?>
            <link href="<?= $item ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
    <?= $this->renderSection('customStyles'); ?>
</head>

<body class="c-app" base-url="<?= base_url(); ?>">
    <?= $this->include('Layout/Components/sidebar'); ?>
    <div class="c-wrapper">
        <?= $this->include('Layout/Components/header'); ?>
        <div class="c-body">
            <main class="c-main">
                <div class="container-fluid">
                    <div class="fade-in">
                        <?= $this->renderSection('content'); ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <div class="modal fade" role="dialog" id="changePassModal" data-backdrop="static" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <table class="table mt-2">
                        <tr class="mt-1">
                            <th style="width: 150px;">Fullname</th>
                            <td style="width: 10px;">:</td>
                            <td><?= $userDt["fullname"] ?? "-" ?></td>
                        </tr>
                        <tr class="mt-1">
                            <th style="width: 150px;">Company</th>
                            <td style="width: 10px;">:</td>
                            <td><?= $userDt["company"] ?? "-" ?></td>
                        </tr>
                        <tr class="mt-1">
                            <th style="width: 150px;">Email</th>
                            <td style="width: 10px;">:</td>
                            <td><?= $userDt["email"] ?? "-" ?></td>
                        </tr>
                        <tr class="mt-1">
                            <th style="width: 150px;">Phone Number</th>
                            <td style="width: 10px;">:</td>
                            <td><?= $userDt["noTelp"] ?? "-" ?></td>
                        </tr>
                        <tr class="mt-1">
                            <th style="width: 150px;">Address</th>
                            <td style="width: 10px;">:</td>
                            <td><?= $userDt["city"] . ' ' . $userDt['country'] . ', ' . $userDt['postalCode'] ?></td>
                        </tr>
                        <tr class="mt-1">
                            <th style="width: 150px;">Tag</th>
                            <td style="width: 10px;">:</td>
                            <td>
                                <?php
                                if (!empty($userDt["tag"])) {
                                    foreach ((explode(",", $userDt["tag"])) as $val) {
                                        echo '<span class="badge badge-primary p-1 mr-1" style="font-size: 13px;">' . $val . '</span>';
                                    }
                                } else {
                                    echo "-";
                                }
                                ?>
                            </td>
                        </tr>
                        <tr class="mt-1">
                            <th style="width: 150px;">Location</th>
                            <td style="width: 10px;">:</td>
                            <td>
                                <?php
                                if (!empty($userDt["tagLocation"])) {
                                    foreach ((explode(",", $userDt["tag"])) as $val) {
                                        echo '<span class="badge badge-primary p-1 mr-1" style="font-size: 13px;">' . $val . '</span>';
                                    }
                                } else {
                                    echo "-";
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                    <div class="row">
                        <div class="form-group mb-0 col-sm-6 mb-0">
                            <label for="password">New Password</label>
                            <input class="form-control" type="password" name="newPass" placeholder="Enter your new Password">
                        </div>
                        <div class="form-group mb-0 col-sm-6 mb-0">
                            <label for="confirmPass">Confirm</label>
                            <input class="form-control" type="password" name="confPass" placeholder="Confirm your passsword">
                        </div>
                    </div>
                    <span class="invalid-feedback-password d-none" id="errCPMessage">
                        <svg aria-hidden="true" fill="currentColor" focusable="false" width="16px" height="16px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
                        </svg>
                        Those passwords didn’t match. Try again.
                    </span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark ml-2" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button type="button" class="btn btn-success ml-2" onclick="changePass()"><i class="fa fa-save"></i> Save</button>
                </div>
            </div>
        </div>
    </div>

    <div id="loader">
        <div class="loadingio-spinner-double-ring">
            <div class="ldio">
                <div></div>
                <div></div>
                <div>
                    <div></div>
                </div>
                <div>
                    <div></div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($js) : ?>
        <?php foreach ($js as $item) : ?>
            <script type="text/javascript" src="<?= $item ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <?= $this->renderSection('customScripts'); ?>
    <script type="text/javascript">
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })

        $(document).ready(function() {
            $('#tblop').DataTable({
                columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                }],
                select: {
                    style: 'os',
                    selector: 'td:first-child'
                },
                order: [
                    [1, 'asc']
                ]
            });

        });

        const changePass = () => {
            let currPass = document.querySelector("input[name=currPass]").value;
            let newPass = document.querySelector("input[name=newPass]").value;
            let confPass = document.querySelector("input[name=confPass]").value;

            if (newPass != confPass) {
                document.getElementById("errCPMessage").classList.add("d-none");
            } else {
                let res = axios.post("<?= site_url("user/changePassword") ?>", {
                    currentPassword: currPass,
                    newPassword: newPass,
                }).then(res => {
                    xhrThrowRequest(res)
                        .then(() => {
                            Toast.fire({
                                title: 'Success Change Password!',
                                icon: 'success'
                            });
                            $("#changePassModal").modal("hide");
                            document.getElementById("errCPMessage").classList.remove("d-none");
                            document.querySelector("input[name=currPass]").value = '';
                            document.querySelector("input[name=newPass]").value = '';
                            document.querySelector("input[name=confPass]").value = '';
                        })
                        .catch((rej) => {
                            if (rej.throw) {
                                throw new Error(rej.message);
                            }
                        });
                })
            }
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable({
                scrollY: "calc(100vh - 300px)",
            });
        });
    </script>
    <script type="text/javascript">
        //filter
        $('#company').select2({
            theme: 'coreui',
            placeholder: "Filter Asset",
            allowClear: true
        })

        $('#area').select2({
            theme: 'coreui',
            placeholder: "Filter Location",
            allowClear: true
        })

        $('#unit').select2({
            theme: 'coreui',
            placeholder: "Filter Tag",
            allowClear: true
        })
    </script>
</body>

</html>