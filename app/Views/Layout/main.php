<!DOCTYPE html>
<html lang="en">

<head>
  <base href="<?= base_url(); ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('/img/icon.png'); ?>" />
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta name="description" content="Core UI">
  <meta name="author" content="CoreUI">
  <meta name="keyword" content="Dashboard,Monitor,CoreUI">
  <title>Nocola - Equipment Record</title>
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css"> -->
  <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css"> -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Rubik">
  <!-- <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet"> -->

  <style type="text/css">

  </style>

  <!-- Main styles for this application-->
  <?php if (isset($css)) : ?>
    <?php foreach ($css as $item) : ?>
      <link href="<?= $item ?>" rel="stylesheet">
    <?php endforeach; ?>
  <?php endif; ?>
  <?= $this->renderSection('customStyles'); ?>
</head>

<body class="c-app">
  <?= $this->include('Layout/components/sidebar'); ?>
  <div class="c-wrapper">
    <?= $this->include('Layout/components/header'); ?>
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
  <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> -->
  <?php if ($js) : ?>
    <?php foreach ($js as $item) : ?>
      <script type="text/javascript" src="<?= $item ?>"></script>
    <?php endforeach; ?>
  <?php endif; ?>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <!-- <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script> -->
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/axios/0.15.3/axios.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js" integrity="sha512-WFN04846sdKMIP5LKNphMaWzU7YpMyCU245etK3g/2ARYbPK9Ub18eG+ljU96qKRCWh+quCY7yefSmlkQw1ANQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script> -->
  <!-- <script type="text/javascript" src="/js/vue-router.js"></script> -->
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
  </script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#example').DataTable({
        scrollY: "calc(100vh - 300px)",
      });
    });
  </script>
  <script type="text/javascript">
    $(function() {

      var start = moment().subtract(29, 'days');
      var end = moment();

      function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
      }

      $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
      }, cb);

      cb(start, end);

    });


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