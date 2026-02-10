<?php
session_start();
require('../config/autoload.php');

$dao = new DataAccess();
$file = new FileUpload();

// Get district ID from URL
$did = $_GET['did'];

// Fetch existing district
$info = $dao->getData('*', 'district', 'did=' . $did);

// Prefill form fields
$elements = [
    "dname" => $info[0]['dname']
];

$form = new FormAssist($elements, $_POST);

$labels = ['dname' => "District Name"];
$rules = [
    "dname" => ["required" => true, "minlength" => 3, "maxlength" => 30, "alphaspaceonly" => true]
];

$validator = new FormValidator($rules, $labels);

// Handle form submission
if (isset($_POST["btn_update"])) {
    if ($validator->validate($_POST)) {

        $data = [
            'dname' => $_POST['dname']
        ];

        if ($dao->update($data, 'district', 'did=' . $did)) {
            // Redirect to district list after update
            header("Location: viewdistrict.php");
            exit();
        } else {
            $msg = "District update failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit District</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #e9f5ec; }
        .form-label { font-weight: bold; }
        .form-section { margin-bottom: 1.5rem; }
        h2 { color: #036621; text-align: center; margin: 30px 0; }
    </style>
</head>
<body>
<div class="container mt-5 mb-5">
    <h2>Edit District</h2>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6 form-section">
                <label class="form-label">District Name</label>
                <?= $form->textBox('dname', ['class'=>'form-control']); ?>
                <span class="text-danger"><?= $validator->error('dname'); ?></span>
            </div>
        </div>

        <?php if (isset($msg)) { ?>
            <div class="alert alert-danger mt-3"><?= $msg; ?></div>
        <?php } ?>

        <div class="mt-4 text-center">
            <button type="submit" name="btn_update" class="btn btn-success btn-lg">Update District</button>
        </div>
    </form>
</div>
</body>
</html>
