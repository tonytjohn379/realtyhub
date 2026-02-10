<?php 
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require('../config/autoload.php'); 

$file = new FileUpload();
$elements = ["cname" => ""];

$form = new FormAssist($elements, $_POST);
$dao = new DataAccess();

$labels = ['cname' => "Category Name"];

$rules = [
    "cname" => ["required" => true, "minlength" => 3, "maxlength" => 30, "alphaspaceonly" => true],
];

$validator = new FormValidator($rules, $labels);

if (isset($_POST["btn_insert"])) {
    if ($validator->validate($_POST)) {
        $data = ['cname' => $_POST['cname']];
        if ($dao->insert($data, "category")) {
            echo "<script>alert('New record created successfully'); window.location='category.php';</script>";
        } else {
            $msg = "Insertion failed. Please try again.";
        }
    }
}

include('includes/admin_header.php'); 
?>

<h2 class="mb-4">Add New Category</h2>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label for="cname" class="form-label fw-semibold">Enter Category Name:</label>
                        <?= $form->textBox('cname', ['class' => 'form-control', 'placeholder' => 'Enter category name']); ?>
                        <div class="text-danger small mt-1"><?= $validator->error('cname'); ?></div>
                    </div>

                    <?php if (!empty($msg)) : ?>
                        <div class="alert alert-danger"><?= $msg; ?></div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" name="btn_insert" class="btn btn-success px-4">Submit</button>
                        <a href="viewcategory.php" class="btn btn-primary">View Categories</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php include('includes/admin_footer.php'); ?>
