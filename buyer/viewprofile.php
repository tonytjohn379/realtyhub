
<?php require('../config/autoload.php'); ?>

<?php
$dao=new DataAccess();



?>
<?php include('header.php'); ?>

    
    <div class="container_gray_bg" id="home_feat_1">
    <div class="container">
    	<div class="row">
            <div class="col-md-12">
                <table  border="1" class="table" style="margin-top:100px;">
                    <tr>
                        
                        <th>Buyer Id</th>
                        <th>Buyer name</th>
                        <th>Buyer phone</th>
                        <th>Buyer Email</th>
                        <th>Buyer Address</th>
                        <th>EDIT/DELETE</th>
                     
                      
                    </tr>
<?php
    
    $actions=array(
    'edit'=>array('label'=>'Edit','link'=>'edit_buyer.php','params'=>array('bid'=>'bid'),'attributes'=>array('class'=>'btn btn-success')),
    
    'delete'=>array('label'=>'Delete','link'=>'edit_buyer.php','params'=>array('bid'=>'bid'),'attributes'=>array('class'=>'btn btn-success'))
    
    );

    $config=array(
        'srno'=>true,
        'hiddenfields'=>array('bid'),
        
    );

        
        
  

   
   $join=array(
     
    );  $fields=array('bid','bname','bphone','bemail','baddress');

    $users=$dao->selectAsTable($fields,'buyer as s',1,$join,$actions,$config);
    
    echo $users;
                    
                    
                   
    
?>
             
                </table>
            </div>    

            
            
            
            
        </div><!-- End row -->
    </div><!-- End container -->
    </div><!-- End container_gray_bg -->
    
    
