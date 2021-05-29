<?php 
    $page_title='Variants';
    $menu_class='active';
    $variant_class='active';
    include 'includes/header.php';
    if (!$session->is_signed_in()) {redirect("login.php");}
    include 'includes/top_nav.php';
    include 'includes/sidebar.php';
    if (isset($_GET['type']) && $_GET['type']!=''){
        $type=$_GET['type'];
        $id=$_GET['id'];
        $update_status=Variant::find($_GET['id']);
        $update_status->status=$type;
        $update_status->save();
        redirect('variants.php');
        $session->message='Variant status updated successfully.';
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
                <a href="#" class="alert-link"></a>.
            </div>
        <?php endif; ?>

        <h1 class="page-header"><?=$page_title?></h1>
        <div class="row">
            <div class="col-xl-12">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title"><?=$page_title?></h4>

                        <div class="panel-heading-btn">
                            <a href="create-variant.php" class="btn btn-xs  btn-primary"><i class="fa fa-plus"></i> Add Variant</a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                        </div>
                    </div>

                    <div class="panel-body">
                        <table id="data-table-combine" class="table table-striped table-bordered table-td-valign-middle">
                            <thead>
                            <tr>
                                <th width="1%">SN</th>
                                <th class="text-nowrap">Variant</th>
                                <th class="text-nowrap">Transmission</th>
                                <th class="text-nowrap">Model</th>
                                <th class="text-nowrap">Variant Code</th>
                                <th class="text-nowrap">Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php  $variant=Variant::all();
                                foreach ($variant as $key=>$value):
                                    ?>
                            <tr class="odd gradeX">
                                <td width="1%" class="f-w-600 text-inverse"><?=$key+1?></td>
                                <td><?=$value->variant_name?></td>
                                <td><?=$value->transmission?></td>
                                <td>
                                    <?php
                                        $model=Model::find($value->model_id);
                                        echo $model->model_name;
                                    ?>
                                </td>
                                <td><?=$value->variant_code?></td>
                                <td>
                                    <?php if ($value->status==1): ?>
                                        <a href="?type=0&id=<?=$value->id?>">Active</a>
                                    <?php else:?>
                                        <a href="?type=1&id=<?=$value->id?>">Inactive</a>
                                    <?php endif;?>
                                </td>
                                <td>
                                    <a href="edit-variant.php?id=<?=$value->id?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>

                                    <?php
                                    $allot=Stock::select('*','model_code="'.$value->variant_code.'"','');
                                    if (!$allot){
                                        echo '
                                    <a href="delete/delete_variant.php?id=<?=$value->id?>" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                        ';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include 'includes/footer.php';?><?php
