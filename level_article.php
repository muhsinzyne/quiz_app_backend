<?php
session_start();
if (!isset($_SESSION['id']) && !isset($_SESSION['username'])) {
	header("location:index.php");
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Questions for Quiz | <?=ucwords($_SESSION['company_name'])?>- Admin Panel </title>
<?php include 'include-css.php';?>
</head>
    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
<?php include 'sidebar.php';?>
<!-- page content -->
                <div class="right_col" role="main">
                    <!-- top tiles -->
                    <br />
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Level Article <small></small></h2>

                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <div class="row">
                                        <form id="register_form" method="POST" action="db_operations.php" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="novalidate">
                                            <h4 class="col-md-offset-1"><b>Create a Question</b></h4>
                                            <input type="hidden" id="level_article" name="level_article" required="" value="1" aria-required="true">
                                            <input type="hidden" id="levelarticle_update" name="levelarticle_update" required="" value="1" aria-required="true">
                                            <div class="form-group">
                                                <label class="control-label col-md-1 col-sm-3 col-xs-12" for="category">Category</label>
                                                <div class="col-md-3 col-sm-3 col-xs-12">
<?php
$db->sql("SET NAMES 'utf8'");
$sql = "select id,`category_name` from `category` order by id desc";
$db->sql($sql);
$res = $db->getResult();
?>
<select name='category' id='category' class='form-control' required>
                                                        <option value=''>Select Main Category</option>
<?php foreach ($res as $row) {?>
																																																																			                                                                        <option value='<?=$row['id']?>'><?=$row['category_name']?></option>
	<?php }?>
</select>
                                                </div>
                                                <label class="control-label col-md-2 col-sm-3 col-xs-12" for="subcategory">Sub Category</label>
                                                <div class="col-md-3 col-sm-3 col-xs-12">
                                                    <select name='subcategory' id='subcategory' class='form-control' >
                                                        <option value=''>Select Sub Category</option>
                                                    </select>
                                                </div>


                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-1 col-sm-3 col-xs-12" for="question">Level</label>
                                                <div class="col-md-3 col-sm-3 col-xs-12">
                                                    <select name='level' id='level' class='form-control' >
                                                        <option value=''>Level</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-2" for="message">Level Learing Article</label>
                                                <div class="col-md-9" id="contentblock">
                                                    <textarea name='content' id='content' class='form-control'> </textarea>
                                                </div>
                                            </div>

                                             <div class="form-group">
                                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                                <button type="submit" id="submit_btn" class="btn btn-success">Update Policy</button>
                                            </div>


                                        </form>
                                        <div class="col-md-12"><hr></div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- footer content -->
<?php include 'footer.php';?>
            <!-- /footer content -->
        </div>
        </div>
        <!-- jQuery -->

        <script>

        function initlevelArticl(){
            tinymce.init({
            selector: '#content',
            height: 900,
            menubar: true,
            plugins: [
                'advlist autolink lists link charmap print preview anchor textcolor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime table contextmenu paste code help wordcount'
            ],
            toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            setup : function(editor) {
                editor.on("change keyup", function(e){
                    //tinyMCE.triggerSave(); // updates all instances
                    editor.save(); // updates this instance's textarea
                    $(editor.getElement()).trigger('change'); // for garlic to detect change
                });
            }
        });
        }
        initlevelArticl();
        </script>


        <script>
        $('#category').on('change',function(e){
            var category_id = $('#category').val();
            $.ajax({
                type:'POST',
                url: "db_operations.php",
                data:'get_subcategories_of_category=1&category_id='+category_id,
                beforeSend:function(){$('#subcategory').html('Please wait..');},
                success:function(result){
                    // alert(result);
                    $('#subcategory').html(result);
                }
            });
        });
        </script>

        <script>
        $('#subcategory').on('change',function(e){
            var subcategory = $('#subcategory').val();
            $.ajax({
                type:'POST',
                url: "db_operations.php",
                data:'get_level_of_subcategories=1&subcategory_id='+subcategory,
                beforeSend:function(){$('#level').html('Please wait..');},
                success:function(result){
                    // alert(result);
                    $('#level').html(result);
                }
            });
        });
        </script>

        <script>
        $('#level').on('change',function(e) {
            var subcategory = $('#subcategory').val();
            var level = $('#level').val();
            $.ajax({
                type:'POST',
                url: "db_operations.php",
                data:'get_level_content=1&subcategory_id='+subcategory+'&level='+level,
                beforeSend:function(){$('#contentblock').html('Please wait..');},
                success:function(result){
                    // alert(result);
                    $('#contentblock').html(result);
                    initlevelArticl();
                }
            });
        });
        </script>




        <script>
            $('#register_form').validate({
                rules:{
                level:"required",
                category:"required",
                a:"required",
                b:"required",
                c:"required",
                d:"required",
                level:"required",
                answer:"required",
                }
            });
        </script>
        <script>
            $('#register_form').on('submit',function(e){
                e.preventDefault();
                var formData = new FormData(this);
                if($("#register_form").validate().form()){
                    var category = $('#category').val();
                    var subcategory = $('#subcategory').val();
                    $.ajax({
                        type:'POST',
                        url: $(this).attr('action'),
                        data:formData,
                        beforeSend:function(){$('#submit_btn').html('Please wait..');$('#submit_btn').prop('disabled', true);},
                        cache:false,
                        contentType: false,
                        processData: false,
                        success:function(result){
                            $('#submit_btn').html('Update Now');
                            $('#submit_btn').prop('disabled', false);
                            // $('#result').html(result);
                            // $('#result').show().delay(8000).fadeOut();
                            // $('#register_form')[0].reset();
                            // $('#category').val(category);
                            // $('#subcategory').val(subcategory);
                            // $('#submit_btn').prop('disabled', false);
                            // $('#questions').bootstrapTable('refresh');
                        }
                    });
                }
            });
        </script>



    </body>
</html>