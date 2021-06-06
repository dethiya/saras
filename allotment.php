<?php 
    $page_title='Allotment';
    $allotment_class='active';
    include 'includes/header.php';
    if (!$session->is_signed_in()) {redirect("login.php");}
    include 'includes/top_nav.php';
    include 'includes/sidebar.php';

    if ($session_user=='administrator')
    {
        $allotment=Stock::select('*','allot_status_id<>5','allot_status_id ASC, model_code ASC, color ASC, invoice_dt DESC');
    }else{
        $allotment=Stock::select('*','delr="'.$session_user->outlet_id.'" AND allot_status_id<>5','allot_status_id ASC, model_code ASC, color ASC, invoice_dt DESC');
    }

    if (isset($_GET['type']) && $_GET['type']!=''){
        $type=$_GET['type'];
        $id=$_GET['id'];
        $update_type=Stock::find($_GET['id']);
        $update_type->type=$type;
        $update_type->save();
        echo '<script>alert("Vehicle type modified successfully.");</script>';
        // redirect('allotment.php');
        // $session->message='Vehicle type updated successfully.';
    }


    ?>
    <div id="content" class="content">
        <ol class="breadcrumb float-xl-right">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active"><?=$page_title?></li>
        </ol>

        <?php if ($session->message): ?>
            <div class="alert alert-success fade show">
                <span class="close" data-dismiss="alert">×</span>
                <strong>Success!</strong>
                <?=$session->message?>
                <a href="#" class="alert-link"></a>
            </div>
        <?php endif; ?>

        <h1 class="page-header"><?=$page_title?></h1>

        <div class="row">
            <div class="col-xl-12">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title"><?=$page_title?></h4>

                        <div class="panel-heading-btn">
                            <a href="swap-chassis.php" class="btn btn-xs  btn-primary"><i class="fa fa-exchange-alt"></i> Swap Chassis</a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                        </div>
                    </div>

                    <div class="panel-body">

                        <table id="data-table-combine" class="table table-striped table-bordered table-td-valign-middle"">
                            <thead>
                            <tr>
                                <th width="1%">SN</th>
                                <th>Status</th>
                                <th width="1%">Actions</th>
                                <th width="1%">Delr Code</th>
                                <th class="text-nowrap">Variant Name</th>
                                <th class="text-nowrap">Variant Code</th>
                                <th class="text-nowrap">Color</th>
                                <th class="text-nowrap">Color Code</th>
                                <th class="text-nowrap">Chassis Prefix</th>
                                <th class="text-nowrap">Chassis #</th>
                                <th class="text-nowrap">Engine #</th>
                                <th class="text-nowrap">Dispatch Date</th>
                                <th class="text-nowrap">Vintage</th>
                                <th class="text-nowrap">Type</th>
                                <th class="text-nowrap">Location</th>
                                <th class="text-nowrap">Vehicle Condition</th>

                                <th class="text-nowrap">Customer Name</th>
                                <th class="text-nowrap">SRM</th>
                                <th class="text-nowrap">RM</th>
                                <th class="text-nowrap">Allotment Date</th>
                                <th class="text-nowrap">Allotment Days</th>
                                <th class="text-nowrap">Remarks</th>
                                <th class="text-nowrap">SMS No</th>
                                <th class="text-nowrap">SMS Inv Dt</th>
                                <th class="text-nowrap">DMS No</th>
                                <th class="text-nowrap">DMS Inv Dt</th>
                                <th class="text-nowrap">MGA</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($allotment as $key=>$value):
                                    $get_srm=Srm::find($value->srm_id);
                                    $get_rm=Rm::find($value->rm_id);
                                    if($value->veh_status=='not ok'){
                                        $veh_stat='<i class="fa fa-exclamation text-danger" ></i>';
                                    }else{
                                        $veh_stat='';
                                    }
                                    ?>
                                <tr>
                                    <td><?=$key+1?></td>
                                    <td><?php
                                        $get_allot_status=AllotStatus::find($value->allot_status_id);
                                            if ($get_allot_status->id==1){
                                                echo '<span class="label label-theme bg-gradient-gray">FPR</span>';
                                            }elseif ($get_allot_status->id==2){
                                                echo '<span class="label label-theme bg-danger">Allot</span>';
                                            }elseif ($get_allot_status->id==3){
                                                echo '<span class="label label-theme bg-warning">BT</span>';
                                            }else{
                                                echo '<span class="label label-theme">Free</span>';
                                            }
                                        ?></td>
                                    <td>

                                            <a href="update-allotment.php?id=<?=$value->id?>" class="btn btn-sm btn-icon btn-success"><span><i class="fa fa-edit"></i></span></a>
                                        <?php if($value->allot_status_id!=4) : ?>
                                            <button  class="btn btn-danger btn-sm btn-icon btnDeallotment" chassis_no="<?=$value->chassis_no?>" vehicle_id="<?=$value->id?>"><i class="fa fa-times"></i></button>
                                        <?php endif;?>

                                    </td>
                                    <td><?=$value->delr?></td>
                                    <td><?php $get_variant=Variant::find_variant($value->model_code); echo $get_variant->variant_name;?></td>
                                    <td><?=$value->model_code.' '.$veh_stat?></td>
                                    <td><?php $get_color=Color::find_color($value->color); echo $get_color->color_name;?></td>
                                    <td><?=$value->color?></td>
                                    <td><?=$value->chassis_prefix?></td>
                                    <td><?=$value->chassis_no?></td>
                                    <td><?=$value->engine_no?></td>
                                    <td data-order="<?=$value->invoice_dt?>">
                                    <?=date('d-M-y',strtotime($value->invoice_dt))?>
                                    </td>
                                    <td>
                                    <?php
                                        $MSIL_dispatch_date = new DateTime(date('d-m-Y',strtotime($value->invoice_dt)));
                                        $current_date = new DateTime();
                                        echo $vintage = $current_date->diff($MSIL_dispatch_date)->format("%a");
                                        ?>
                                    </td>
                                    <td>
                                    <?php if ($value->type==1): ?>
                                        <a class='btnActivate' vehicle_id="<?=$value->id?>" vehicleType="0">Type II</a>
                                    <?php else:?>
                                        <a class='btnActivate' vehicle_id="<?=$value->id?>" vehicleType="1">Type I</a>
                                    <?php endif;?>
                                    </td>
                                    <td><a href="update-location.php?id=<?=$value->id?>"><?php $get_stock_location=StockLocation::find($value->stock_location); echo $get_stock_location->stock_loc_name; ?></a></td>
                                    <td><?php
                                        if($value->veh_status=='ok'){
                                            echo "OK";
                                        }else{
                                            echo 'Not Ok';
                                        }
                                        ?></td>
                                    <td><?php echo empty($value->customer_name) ? '-' : $value->customer_name;?></td>
                                    <td><?php echo $value->srm_id>0 ? $get_srm->srm_name : '-'; ?>
                                    <td><?php echo $value->rm_id>0 ? $get_rm->rm_name : '-'; ?>
                                    </td>
                                    <td><?php echo !empty($value->customer_name) ? date('d-M-y',strtotime($value->allotment_dt)) : '-'; ?></td>
                                    <td><?php
                                        if (!empty($value->customer_name)){
                                        $Alt_dt=new DateTime($value->allotment_dt);
                                        $allot_dt=$current_date->diff($Alt_dt)->format("%a");
                                        echo $allot_dt;
                                        }else{
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo empty($value->allotment_remark)?'-':$value->allotment_remark;?></td>
                                    <td><?php echo empty($value->sms_inv_no)?'-':$value->sms_inv_no;?></td>
                                    <td><?php echo empty($value->sms_inv_no) ? '-' : date('d-M-y',strtotime($value->sms_inv_dt)); ?></td>
                                    <td><?php echo empty($value->dms_inv_no)?'-':$value->dms_inv_no;?></td>
                                    <td><?php echo empty($value->dms_inv_no) ? '-' : date('d-M-y',strtotime($value->dms_inv_dt)); ?></td>
                                    <td><?php echo empty($value->mga_amount) ? '-' : $value->mga_amount; ?></td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php //include 'includes/delete_modal.php';?>

<script>
    function new_deallotment(deallotid){
        // var new_chassis = $(this).attr("de_chassis");
        if(confirm("Are you sure, you want to deallot the vehicle?")){
            window.location.href='deallotment.php?id='+deallotid+'';
            return true;
        }
    }
</script>
<?php include 'includes/footer.php';?>
