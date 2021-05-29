<?php 
    $page_title='Deliveries';
$delivery_class='active';
$delivery_menu_class='active';
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
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                        </div>
                    </div>

                    <div class="panel-body">
                        <table id="data-table-combine" class="table tables table-striped table-bordered table-td-valign-middle">
                            <thead>
                            <tr>
                                <th width="1%">SN</th>
                                <th>Status</th>
                                <th width="1%">Actions</th>
                                <th width="1%">Delr Code</th>
                                <th class="text-nowrap">Variant Name</th>
                                <th class="text-nowrap">Color</th>
                                <th class="text-nowrap">Chassis #</th>
                                <th class="text-nowrap">Engine #</th>
                                <th class="text-nowrap">Dispatch Date</th>
                                <th class="text-nowrap">Location</th>
                                <th class="text-nowrap">Customer Name</th>
                                <th class="text-nowrap">SRM</th>
                                <th class="text-nowrap">RM</th>
                                <th class="text-nowrap">SMS No</th>
                                <th class="text-nowrap">SMS Inv Dt</th>
                                <th class="text-nowrap">DMS No</th>
                                <th class="text-nowrap">DMS Inv Dt</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($allotment as $key=>$value):
                                    $get_srm=Srm::find($value->srm_id);
                                    $get_rm=Rm::find($value->rm_id);
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
                                    <td class="text-center">
                                       <?php if($value->allot_status_id!=4) : ?>
<!--                                           <input type="button" onclick="new_delivery(-->
<!--                                           //)" class="btn btn-primary btn-sm" value="Deliver">-->
                                       <button  class="btn btn-primary btn-sm btn-icon btnDeliver" chassis_no="<?=$value->chassis_no?>" vehicle_id="<?=$value->id?>"><i class="fa fa-check"></i></button>
                                        <?php endif;?>
                                    </td>
                                    <td><?=$value->delr?></td>
                                    <td><?php $get_variant=Variant::find_variant($value->model_code); echo $get_variant->variant_name;?></td>
                                    <td><?php $get_color=Color::find_color($value->color); echo $get_color->color_name;?></td>
                                    <td><?=$value->chassis_no?></td>
                                    <td><?=$value->engine_no?></td>
                                    <td><?=date('d-m-Y',strtotime($value->invoice_dt))?></td>
                                    <td><a href="javascript:void(0)"><?php $get_stock_location=StockLocation::find($value->stock_location); echo $get_stock_location->stock_loc_name; ?></a></td>
                                    <td><?php echo empty($value->customer_name) ? '-' : $value->customer_name;?></td>
                                    <td><?php echo $value->srm_id>0 ? $get_srm->srm_name : '-'; ?>
                                    <td><?php echo $value->rm_id>0 ? $get_rm->rm_name : '-'; ?>
                                    </td>
                                    <td><?php echo empty($value->sms_inv_no)?'-':$value->sms_inv_no;?></td>
                                    <td><?php echo empty($value->sms_inv_no) ? '-' : date('d-m-Y',strtotime($value->sms_inv_dt)); ?></td>
                                    <td><?php echo empty($value->dms_inv_no)?'-':$value->dms_inv_no;?></td>
                                    <td><?php echo empty($value->dms_inv_no) ? '-' : date('d-m-Y',strtotime($value->dms_inv_dt)); ?></td>
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
<?php include 'includes/footer.php';?>
