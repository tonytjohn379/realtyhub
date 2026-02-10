<?php 

 require('../config/autoload.php'); 
include("header.php");

$file=new FileUpload();
$elements=array(
        "property_id"=>"","buyer_id"=>"","date"=>"","token"=>"");


$form=new FormAssist($elements,$_POST);



$dao=new DataAccess();

$labels=array('property_id'=>"property id","buyer_id"=>"buyer id ","date"=>"date ","token"=>"token ");

$rules=array(
    "property_id"=>array("required"=>true),
    "buyer_id"=>array("required"=>true),
    "date"=>array("required"=>true),
    "token"=>array("required"=>true,"minlength"=>0,"maxlength"=>10,"integeronly"=>true)
     
);
    
    
$validator = new FormValidator($rules,$labels);

if(isset($_POST["btn_insert"]))
{

if($validator->validate($_POST))
{
	
if($fileName=$file->doUploadRandom($_FILES['limage'],array('.jpg','.png','.jpeg','.PNG'),100000,5,'../uploads'))
		{

$data=array(

        'property_id'=>$_POST['property_id'],
        'buyer_id'=>$_POST['buyer_id'],
        'date'=>$_POST['date'],
        'token'=>$_POST['token']
        
    );
  
    if($dao->insert($data,"booking"))
    {
        echo "<script> alert('New record created successfully');</script> ";
header('location:booking.php');
    }
    else
        {$msg="Registration failed";} ?>

<span style="color:red;"><?php echo $msg; ?></span>

<?php
    
}
else
echo $file->errors();
}

}


?>
<html>
<head>
</head>
<body>

 <form action="" method="POST" enctype="multipart/form-data">
 
<div class="row">
                    <div class="col-md-6">
property id:

<?php
        $options = $dao->createOptions('lname','lid',"property");
        echo $form->dropDownList('property_id',array('class'=>'form-control'),$options); ?>
<?= $validator->error('property_id'); ?>

</div>
</div>

<div class="row">
                    <div class="col-md-6">
buyer id:

<?php
        $options = $dao->createOptions('bname','bid',"buyer");
        echo $form->dropDownList('buyer_id',array('class'=>'form-control'),$options); ?>
<?= $validator->error('buyer_id'); ?>

</div>
</div>

<div class="row">
                    <div class="col-md-6">
date:

<?= $form->textBox('date',array('class'=>'form-control')); ?>
<?= $validator->error('date'); ?>

</div>
</div>

<div class="row">
                    <div class="col-md-6">
token:

<?= $form->textBox('token',array('class'=>'form-control')); ?>
<?= $validator->error('token'); ?>

</div>
</div>


<button type="submit" name="btn_insert" >Submit</button>
</form>


</body>

</html>


