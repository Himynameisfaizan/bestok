<?php
include "db-conn.php";
?>
<!DOCTYPE html>
<html lang="zxx">
<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Sales</title>
    <link rel="icon" href="img/logo.png" type="image/png">

    <?php include "links.php"; ?>
</head>

<body class="crm_body_bg">

    <?php
    include "header.php";
    ?>
    <section class="main_content dashboard_part large_header_bg">
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-lg-12 p-0">
                    <?php include "top_nav.php"; ?>
                </div>
            </div>
        </div>


        <div class="main_content_iner ">
            <div class="container-fluid p-0 sm_padding_15px">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="main_content_iner">
                            <div class="container-fluid p-0 sm_padding_15px">
                                <div class="row justify-content-center">


                                    <div class="col-lg-12">
                                        <div class="white_card card_height_100 mb_30">
                                            <div class="white_card_header">
                                                <div class="box_header m-0">
                                                    <div class="main-title">
                                                        <h2 class="m-0">Fill Category Details</h2>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="white_card_body">
                                                <div class="card-body">
                                                    <form id="myform" action="functions.php" method="post"
                                                        enctype="multipart/form-data">
                                                        <div class="row mb-3">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="inputEmail4">Category
                                                                    Name</label>
                                                                <input type="text" class="form-control" name="cate_name"
                                                                    id="inputEmail4"
                                                                    placeholder="Fill the category name" required />
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="inputEmail4">Meta
                                                                    Title</label>
                                                                <input type="text" class="form-control"
                                                                    name="meta_title" id="inputEmail4"
                                                                    placeholder="Meta Title" required />
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="inputEmail4">Meta
                                                                    Keyword</label>
                                                                <input type="text" class="form-control" name="meta_key"
                                                                    id="inputEmail4" placeholder="Meta Keyword"
                                                                    required />
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="inputEmail4">Meta
                                                                    Discription</label>
                                                                <input type="text" class="form-control" name="meta_desc"
                                                                    id="inputEmail4" placeholder="Meta Discription"
                                                                    required />
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label"
                                                                    for="inputState">Status</label>
                                                                <select id="inputState" name="status"
                                                                    class="form-control" required>
                                                                    <!-- <option selected>Choose...</option> -->
                                                                    <option value="1">Active</option>
                                                                    <option value="0">Deactive</option>
                                                                </select>
                                                            </div>
                                                            
                                                            <div class="col-md-6 mb-3">
        <label class="form-label" for="display_order">Display Order</label>
        <input type="number"
            class="form-control"
            name="display_order"
            id="display_order"
            placeholder="Enter Display Order"
            min="1"
            value="1"
            required>
    </div>

                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="inputEmail4">Category
                                                                    Images</label>
                                                                <input type="file" class="form-control"
                                                                    name="imageUpload" id="imageUpload" accept="image/*"
                                                                    placeholder="Meta Keyword"  />
                                                            </div>
                                                        </div>

                                                        <button type="submit" class="btn btn-primary"
                                                            name="add-categories">
                                                            Add Category
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <?php include "footer.php"; ?>

        <script>
            const form = document.getElementById('myForm');

            form.addEventListener('submit', function (event) {
                const select = document.getElementById('category');
                if (!select.value) {
                    alert('Please select a valid category.');
                    event.preventDefault(); // Prevent form submission
                }
            });
        </script>