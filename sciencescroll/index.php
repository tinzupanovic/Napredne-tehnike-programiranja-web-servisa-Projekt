<!DOCTYPE html>
<html lang="en">
    <?php 
        session_start();
        include ("config.php");

        if(isset($_GET['menu'])) { $menu = (int)$_GET['menu']; }
        if(isset($_GET['id'])) { $id = (int)$_GET['id']; }
        
        if (!isset($menu)) { $menu = 1; } 
    ?>
    <head>
        <meta charset="UTF-8">
	    <meta name="author" content ="Tin Županović"/>
	    <meta name="description" content ="sciencescroll"/>
        <link rel="stylesheet" href="css/style.css">
	    <title>ScienceScroll</title>
        <link rel="icon" type="image/png" sizes="32x32" href="images/favicon.png">
    </head>
    <body>
        <header>
            <nav>
                <?php include("menu.php") ?>
            </nav>
	    </header>
        <article>
            <?php 
                if ((!isset($menu) || $menu == 1) && !isset($id)) { include("home.php"); }
                else if ($menu == 2) { include("articles.php"); }
                else if ($menu == 5) { include("register.php"); }
                else if ($menu == 6) { include("login.php"); }
                else if ($menu == 7) { include("admin.php"); }
                else if ($menu == 8) { include("newpost.php"); }
                else if ($menu == 9 && isset($_GET['idtoedit'])) { include("edit_article.php"); }
                else if ($menu == 11 && isset($_GET['useridtoedit'])) { include("edit_user.php"); }
                else if ($menu == 10) { include("arxiv.php"); }
                else if (isset($id)) { include("view_article.php"); }
            ?>
        </article>
        <footer>
            <p>© <?php echo date('Y'); ?> ScienceScroll</p>
        </footer>
    </body>
</html>

