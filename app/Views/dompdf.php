<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Nocola | Equipment Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5" id="app">

        <center>
            <h2><?= $title; ?></h2>
        </center>
        <center>
            <table class="table table-hover mt-4" style="vertical-align: middle; text-align: center;">
                <thead style="background-color: red; color: white">
                    <tr>
                        <th>#</th>
                        <th>Company</th>
                        <th>Area</th>
                        <th>Unit</th>
                        <th>Equipment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $ii = 1;
                    foreach ($data as $i) : ?>
                        <tr>
                            <td><?= $ii++; ?></td>
                            <td><?= $i['company']; ?></td>
                            <td><?= $i['area']; ?></td>
                            <td><?= $i['unit']; ?></td>
                            <td><?= $i['equipment']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </center>
    </div>
</body>

</html>