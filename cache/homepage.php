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
    <title>BlogCMS - Home Page</title>
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
        

<div class="row">
    <div class="col-md-12">
        <div class="titlepage">
            <h2 class="">Welcome in&nbsp;&nbsp;<img id="logotipo_home" src="public/static/images/blogram_scritta.png"></h2>
            <span>There are currently <?php echo $n_blogs ?> blogs for a total of <?php echo $n_posts ?> posts!</span>
        </div>
    </div>
</div>

<h1 class="mt-5 text-theme-color mb-5">Most visited blogs</h1>
<div class="row">
    <?php foreach($blogs as $blog): ?>
        <div class="col-md-6 padding_bottom2" style="margin-bottom: 50px;">
    <h1><a href="blog?id=<?php echo $blog['id'] ?>"><?php echo $blog['name'] ?></a></h1>
    <div class="our_img">
        <figure>
            <a href="blog?id=<?php echo $blog['id'] ?>"><img style="width: 540px; height: 336px;border: 1px solid #DDD;" src="public/static/contents/blog_images/<?php echo $blog['path_copertina'] ?: 'default.png' ?>" alt="#"/></a>
        </figure>
    </div>
    <div>
    </div>
    <div class="our_text_box three_box">
        <div>
            <span><b>Category:</b> <?php echo $blog["parent_argument_name"]?ucfirst($blog["parent_argument_name"])." -> ":"" ?> <?php echo ucfirst($blog["argument_name"]) ?></span>
        </div>
        <div>
            <span>
                <b>Author:</b> @<?php echo $blog["author_username"] ?> <?php if (array_key_exists("username", $_SESSION) && $_SESSION["username"] == $blog['author_username']): ?> <span class="badge badge-info">You</span> <?php endif ?>
            </span>
        </div>
        <div style="margin-bottom: 10px;">
            <?php if($blog["coauthor_username"] != null): ?>
                <b>Co-Author:</b> @<?php echo $blog["coauthor_username"] ?> <?php if (array_key_exists("username", $_SESSION) && $_SESSION["username"] == $blog['coauthor_username']): ?> <span class="badge badge-info">You</span> <?php endif ?>
            <?php else: ?>
            <b>Co-Author:</b>
            <?php endif ?>
        </div>

        <div>
            <a href="blog?id=<?php echo $blog['id'] ?>"><button class="btn">VIEW BLOG</button></a>
            <span>(<?php echo $blog["visite"] ?> visite)</span>
        </div>
    </div>
</div>
    <?php endforeach ?>
</div>

<hr>

<h1 class="mt-5 text-theme-color mb-5">Most visited post</h1>
<div class="row">
    <?php foreach($posts as $post): ?>
        <div class="row margin_top_30" style="text-align: left !important">
    <div class="col-sm-2">
        <div class="picture">
            <a href="post?id=<?php echo $post['id'] ?>">
                <img alt="Opt wizard thumbnail" style="border: 1px solid #DDD;" src="public/static/contents/post_images/<?php echo explode(',', $post['images'])[0] ?: 'default.png' ?>">
            </a>
        </div>
    </div>
    <div class="col-sm-6">
        <h4>
            <b><a href="post?id=<?php echo $post['id'] ?>" class="posttitle"><?php echo $post['title'] ?></a></b>
            <div class="label label-info postauthor">@<?php echo $post['author_username'] ?> <?php if (array_key_exists("username", $_SESSION) && $_SESSION["username"] == $post['author_username']): ?> <span class="badge badge-info">You</span> <?php endif ?> in "<?php echo $post['blog_name'] ?>"</div>
        </h4>
        <h5>
            <i class="fa fa-calendar"></i>
            <?php echo $post['date_hours'] ?> <span>(<?php echo $post["visite"] ?> views)</span>
        </h5>

    </div>
    <div class="col-sm-4" data-no-turbolink="">
        <div style="height: 40px;">
        <a class="btn btn-info btn-download btn-round pull-right makeLoading" href="post?id=<?php echo $post['id'] ?>">
            <i class="fa fa-share"></i> View
        </a>
        </div>
        <?php if (array_key_exists("id", $_SESSION) && $_SESSION["id"] == $post['id_author']): ?>
        <div style="height: 40px;">
        <a class="btn btn-info btn-danger btn-round pull-right makeLoading" href="deletepost?id=<?php echo $post['id'] ?>"
           onclick="return confirm('Are you sure?');">
            <i class="fa fa-trash"></i> Delete
        </a>
        </div>
        <?php endif ?>
    </div>
</div>
    <?php endforeach ?>
</div>

<hr>

<h1 class="mt-5 text-theme-color mb-2">Most visited categories</h1>
<div class="row">
    <?php foreach($arguments as $argument): ?>
    <div class="col-12 padding_bottom2">
        <a href="blogs?id_argument=<?php echo $argument['id'] ?>"><h2><?php echo $argument['name'] ?> (<?php echo $argument["visite"] ?> visite)</h2>
        </a>
    </div>
    <?php endforeach ?>
</div>

<hr>

<h1 class="mt-5 text-theme-color mb-2">Most popular authors</h1>
<div class="row">
    <?php foreach($authors as $author): ?>
    <div class="col-12 padding_bottom2">
        <a href="blogs?id_author=<?php echo $author['id'] ?>"><h2><?php echo $author['username'] ?> (<?php echo $author["blogs_count"] ?> blog)</h2>
        </a>
    </div>
    <?php endforeach ?>
</div>

<hr>

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



