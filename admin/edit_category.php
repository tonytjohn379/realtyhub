<?php
session_start();
require('../config/autoload.php'); 

$dao = new DataAccess();

// Get category ID from URL
$cid = $_GET['cid'];

// Fetch existing category
$info = $dao->getData('*', 'category', 'cid=' . $cid);

// Prefill form fields
$elements = [
    "cname" => $info[0]['cname']
];

$form = new FormAssist($elements, $_POST);

$labels = ['cname' => "Category Name"];
$rules = [
    "cname" => ["required" => true, "minlength" => 3, "maxlength" => 30, "alphaspaceonly" => true]
];

$validator = new FormValidator($rules, $labels);

// Handle form submission
if (isset($_POST["btn_update"])) {
    if ($validator->validate($_POST)) {

        $data = [
            'cname' => $_POST['cname']
        ];

        if ($dao->update($data, "category", "cid=" . $cid)) {
            // Redirect to category list after update
            header("Location: viewcategory.php");
            exit();
        } else {
            $msg = "Category update failed!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Category</title>
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
    <h2>Edit Category</h2>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6 form-section">
                <label class="form-label">Category Name</label>
                <?= $form->textBox('cname', ['class'=>'form-control']); ?>
                <span class="text-danger"><?= $validator->error('cname'); ?></span>
            </div>
        </div>

        <?php if (isset($msg)) { ?>
            <div class="alert alert-danger mt-3"><?= $msg; ?></div>
        <?php } ?>

        <div class="mt-4 text-center">
            <button type="submit" name="btn_update" class="btn btn-success btn-lg">Update Category</button>
        </div>
    </form>
</div>
</body>
</html>
