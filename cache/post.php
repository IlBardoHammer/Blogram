<?php class_exists('TemplateCore') or exit; ?>
<!doctype html>
<html lang="en">
<head>
    <!-- basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <!-- site metas -->
    <title>BlogCMS - <?php echo $post['title'] ?></title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- bootstrap css -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- Responsive-->
    <link rel="stylesheet" href="public/static/css/responsive.css">
    <!-- favicon -->
    <link rel="icon" href="public/static/images/favicon.ico" type="image/gif"/>
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="public/static/css/jquery.mCustomScrollbar.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css" media="screen">

    <style>
        :root{
            --primary-color: <?php if(isset($theme) && $theme['primary_colour']): ?> <?php echo $theme['primary_colour'] ?> <?php else: ?>#ed3c95<?php endif ?> !important;
            --secondary-color: <?php if(isset($theme) && $theme['secondary_colour']): ?> <?php echo $theme['secondary_colour'] ?> <?php else: ?>#c21a78<?php endif ?> !important;
        }

        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>


    <!-- Custom styles for this template -->
    <link href="public/static/css/base.css" rel="stylesheet">

    

    <!-- Javascript files-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="public/static/js/jquery-3.0.0.min.js"></script>

    <!-- sidebar -->
    <script src="public/static/js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="public/static/js/custom.js"></script>
    <script src="https:cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>



    
</head>
<!-- body -->
<body class="main-layout">
<!-- loader  -->
<div class="loader_bg">
    <div class="loader"><img src="public/static/images/loading.gif" alt="#"/></div>
</div>
<!-- end loader -->

<?php list($search_arguments, $n_search_arguments) = ArgumentModel::getAllTopArguments(); ?>


<!-- header -->
<header class="position-relative">
    <!-- header inner -->
    <div class="head_top">
        <div class="header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col logo_section">
                        <div class="full">
                            <div class="center-desk">
                                <div class="logo">
                                    <a href="home" title="Homepage">
                                        <img style="width: 65px;padding-top:10px;" src="public/static/images/Blogram_logo.png">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                        <div class="row">
                            <div class="col">
                                <nav class="navigation navbar navbar-expand-md navbar-dark d-block">
                                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                                            data-target="#navbarsExample04"
                                            aria-controls="navbarsExample04" aria-expanded="false"
                                            aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"></span>
                                    </button>
                                    <div class="collapse navbar-collapse" id="navbarsExample04">
                                        <ul class="navbar-nav mr-auto d-lg-flex align-items-center">
                                            <section class="banner_main d-block">
                                                <div class="container">
                                                    <div class="row d_flex justify-content-end">
                                                        <div class="col justify-content-end">
                                                            <div class="pl-2 pr-2 pt-2 pb-2 bg-white rounded float-right"
                                                                 id="div_form_search">
                                                                <form class="form-inline" id="form_search" action="blogs"
                                                                      method="POST">
                                                                    <select multiple data-style="bg-white shadow-sm "
                                                                            class="selectpicker form-control form-control-sm"
                                                                            name="search_arguments[]">
                                                                        <?php foreach($search_arguments as $s_argument): ?>
                                                                        <option value="<?php echo $s_argument['id'] ?>">
                                                                            <?php echo ucfirst($s_argument["name"]) ?>
                                                                        </option>
                                                                        <?php endforeach ?>
                                                                    </select>
                                                                    <div class="form-group ml-2">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="search_author_username"
                                                                               placeholder="Inserisci username"
                                                                               name="search_author_username">
                                                                    </div>
                                                                    <div class="form-group ml-2">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="search_blog_name"
                                                                               placeholder="Inserisci il nome del blog"
                                                                               name="search_blog_name">
                                                                    </div>
                                                                    <div class="form-group ml-2">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="search_title_post"
                                                                               placeholder="Inserisci il titolo del post"
                                                                               name="search_title_post">
                                                                    </div>
                                                                    <button type="submit"
                                                                            class="btn ml-2 btn-sm search-btn-filter"><i
                                                                            class="fa fa-search"></i> Cerca
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                            <li class="nav-item h-100">
                                                <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true): ?>
                                                <button type="button" class="btn rounded-circle bg-theme-color" id="user_btn"><?php echo ucfirst($_SESSION["username"][0]) ?>
                                                </button>

                                                <ul class="pt-2 pb-2 pl-4 pr-4" id="user_actions_div">
                                                    <li class="mb-2 pl-1 pr-1"><a class="" href="myblogs">My Blogs</a></li>
                                                    <li class="mb-2 pl-1 pr-1"><a class="" href="author">Account</a></li>
                                                    <li class="mb-2 pl-1 pr-1"><a class="" href="logout">Logout</a></li>
                                                </ul>
                                                <?php else: ?>
                                                <a class="btn text-white" id="login_visitor" href="login">Login</a>
                                                <a class="btn text-white" id="register_visitor" href="register">Register</a>
                                                <?php endif ?>
                                            </li>
                                        </ul>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- end banner -->



<div class="blog_main" role="main">
    <div class="container w-100">
        

<div style="position: absolute; left: 50px;">
    <a href="blog?id=<?php echo $post['id_blog'] ?>">
        <button class="btn btn-primary"><i class="fa fa-angle-left"></i> <?php echo $post['blog_name'] ?></button>
    </a>
</div>

<div class="text-center mt-5 w-100" style="clear:both;">
    <h1 class="" style="font-size:2.5rem;"><?php echo $post["title"] ?></h1>

    <img style="width: 70%; border: 3px #00b5f0 solid; "
         src="public/static/contents/post_images/<?php echo explode(',',$post['images'])[0] ?: 'default.png' ?>" alt="#" class="mb-2 blog_copertina"/>

    <?php if (sizeof(explode(',',$post['images'])) > 1): ?>

    <?php foreach (explode(',',$post['images']) as $k => $v): ?>
    <?php if ($k < 1): ?>
    <?php continue; ?>
    <?php endif ?>

    <img style="margin: 10px 5px; width: 30%; border: 1px #00b5f0 solid; "
         src="public/static/contents/post_images/<?php echo $v ?: 'default.png' ?>" alt="#" class="mb-2 blog_copertina"/>

    <?php endforeach ?>

    <?php endif ?>

    <?php if (array_key_exists("id", $_SESSION) && $_SESSION["id"] == $post['id_author']): ?>
    <div class="mt-2" style="">
        <a href="deletepost?id=<?php echo $post['id'] ?>" onclick="return confirm('Are you sure?');">
            <button class="btn btn-danger"><i class="fa fa-trash"></i> DELETE POST</button>
        </a>
    </div>
    <?php endif ?>

    <hr>

    <div>
        <h4>
            <div class="label label-info postauthor">@<?php echo $post['author_username'] ?> <?php if (array_key_exists("username",
                $_SESSION) && $_SESSION["username"] == $post['author_username']): ?> <span
                        class="badge badge-info">You</span> <?php endif ?> in "<?php echo $post['blog_name'] ?>"
            </div>
        </h4>
        <h5>
            <i class="fa fa-calendar"></i>
            <?php echo $post['date_hours'] ?> <span>(<?php echo $post["visite"] ?> views)</span>
        </h5>
    </div>

    <hr>

    <p>
        <?php echo $post['text'] != '' ? $post['text'] : "NO POST CONTENT" ?>
    </p>

    <hr>
    <div class="container mt-5" style="text-align: left !important;">
        <h1>Comments (<?php echo sizeof($comments) ?>)</h1>

        <?php foreach ($comments as $k => $comment): ?>
        <hr>
        <div class="d-flex row">
            <div class="col-md-8">
                <div class="d-flex flex-column comment-section">
                    <div class="bg-white p-2">
                        <div class="d-flex flex-row user-info">
                            <div class="d-flex flex-column justify-content-start">
                                <span class="d-block font-weight-bold name">@<?php echo $comment["author_username"] ?: "Visitor". $comment["id_visitor"] ?> <?php if (array_key_exists("username", $_SESSION) && $_SESSION["username"] == $comment["author_username"]): ?> <span class="badge badge-info">You</span> <?php endif ?></span>
                                <span class="date text-black-50">
                                    <i class="fa fa-calendar"></i> <?php echo $comment["date_hours"] ?>
                                </span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="comment-text"><?php echo $comment["text_comment"] ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach ?>
    </div>

    <hr>

    <div class="bg-light p-2" style="text-align: left">
        <h2>New comment</h2>
        <form id="form_comment" action="post?id=<?php echo $post['id'] ?>" method="POST">
            <div class="d-flex flex-row align-items-start">
                <textarea name="comment" placeholder="add a new comment..." class="form-control ml-1 shadow-none textarea"></textarea>
            </div>
            <div class="mt-2 text-right">
                <button class="btn btn-primary btn-sm shadow-none" type="submit">Post comment</button>
            </div>
        </form>
    </div>

</div>


    </div>
</div>

<!--  footer -->
<footer>
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="cont">
                        <h3><strong class="multi text-theme-color"> IL BARDO</strong><br>
                            <?php echo date("Y") ?>
                        </h3>
                    </div>
                </div>
<!--                <div class="col-md-12">-->
<!--                    <ul class="social_icon">-->
<!--                        <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>-->
<!--                        <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></i></a></li>-->
<!--                        <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></i></a></li>-->
<!--                    </ul>-->
<!--                </div>-->
            </div>
        </div>
        <div class="copyright">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <p>IL BARDO?? <?php echo date("Y") ?> All Rights Reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- end footer -->

</body>
</html>



