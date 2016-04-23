<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Shankara Subba Aiyar's Mahabharata</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">    
	<link href='https://fonts.googleapis.com/css?family=Asap' rel='stylesheet' type='text/css'>    
	<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>    

    <!-- Bootstrap Core CSS -->

    <!-- Custom CSS -->
    <link href="../css/thumbnail-gallery.css" rel="stylesheet">
    <link href="../css/carousel.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Shankara Subba Aiyar's Mahabharata</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="../index.html#about">About</a>
                    </li>
                    <li>
                        <a href="../index.html#books">Books</a>
                    </li>
                    <li>
                        <a href="../index.html#gallery">Gallery</a>
                    </li>
                    <li>
                        <a href="../index.html#contact">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

<div class="container">
  <div class="row">
     <div class="col-lg-12">
		<div class="contents">


<?php
include("connect.php");

$book_id = $_GET['book_id'];

$db = mysql_connect("localhost",$user,$password) or die("Not connected to database");
$rs = mysql_select_db($database,$db) or die("No Database");
mysql_set_charset("utf8");

$stack = array();
$p_stack = array();
$first = 1;
$flag = 1;
$li_id = 0;
$ul_id = 0;

$plus_link = "<img src=\"../images/plus.gif\" alt=\"\" onclick=\"display_block(this)\" />";
//$plus_link = "<a href=\"#\" onclick=\"display_block(this)\"><img src=\"plus.gif\" alt=\"\"></a>";
$bullet = "<img src=\"../images/bullet_1.gif\" alt=\"\" />";

$query = "select * from books_toc where book_id='$book_id'";
$result = mysql_query($query);
$num_rows = mysql_num_rows($result);

if($num_rows)
{
    echo "<div class=\"treeview\">";
    for($i=1;$i<=$num_rows;$i++)
	{
		$row = mysql_fetch_assoc($result);
        $book_id = $row['book_id'];
		$btitle = $row['btitle'];
		$level = $row['level'];
		$title = $row['title'];
		$page = $row['page'];
		$slno = $row['slno'];
           
        $btitle = preg_replace('/-/'," &ndash; ", $btitle);
        $btitle = preg_replace('/—/'," &mdash; ", $btitle);
        if($flag)
        {
            echo "<div class=\"book\"><h1>$btitle</h1></div>";
            echo "<div class=\"toc_title\">Table of Contents</div>";
            $flag = 0;
        }
        $title = preg_replace('/—/',"",$title);
        $title = preg_replace('/-/'," &ndash; ", $title);
        if($first)
        {
            array_push($stack,$level);
            $ul_id++;
            echo "<ul id=\"ul_id$ul_id\">\n";
            array_push($p_stack,$ul_id);
            $li_id++;
            $deffer = display_tabs($level) . "<li id=\"li_id$li_id\">:rep:<span class=\"s1\"><a href=\"../Volumes/$book_id/index.djvu?djvuopts&amp;page=$page.djvu&amp;zoom=page\" target=\"_blank\">$title</a></span>";
            $first = 0;
        }
        elseif($level > $stack[sizeof($stack)-1])
        {
            $deffer = preg_replace('/:rep:/',"$plus_link",$deffer);
            echo $deffer;
            $ul_id++;
            $li_id++;
            array_push($stack,$level);
            array_push($p_stack,$ul_id);
            $deffer = "\n" . display_tabs(($level-1)) . "<ul class=\"dnone\" id=\"ul_id$ul_id\">\n";
            $deffer = $deffer . display_tabs($level) ."<li id=\"li_id$li_id\">:rep:<span class=\"s2\"><a href=\"../Volumes/$book_id/index.djvu?djvuopts&amp;page=$page.djvu&amp;zoom=page\" target=\"_blank\">$title</a></span>";
        }
        elseif($level < $stack[sizeof($stack)-1])
        {
            $deffer = preg_replace('/:rep:/',"$bullet",$deffer);
            echo $deffer;
            for($k=sizeof($stack)-1;(($k>=0) && ($level != $stack[$k]));$k--)
            {
                echo "</li>\n". display_tabs($level) ."</ul>\n";
                $top = array_pop($stack);
                $top1 = array_pop($p_stack);
            }
            $li_id++;
            $deffer = display_tabs($level) . "</li>\n";
            $deffer = $deffer . display_tabs($level) ."<li id=\"li_id$li_id\">:rep:<span class=\"s1\"><a href=\"../Volumes/$book_id/index.djvu?djvuopts&amp;page=$page.djvu&amp;zoom=page\" target=\"_blank\">$title</a></span>";
        }
        elseif($level == $stack[sizeof($stack)-1])
        {
            $deffer = preg_replace('/:rep:/',"$bullet",$deffer);
            echo $deffer;
            $li_id++;
            $deffer = "</li>\n";
            $deffer = $deffer . display_tabs($level) ."<li id=\"li_id$li_id\">:rep:<span class=\"s1\"><a href=\"../Volumes/$book_id/index.djvu?djvuopts&amp;page=$page.djvu&amp;zoom=page\" target=\"_blank\">$title</a></span>";
        }
    }
    $deffer = preg_replace('/:rep:/',"$bullet",$deffer);
    echo $deffer;

    for($i=0;$i<sizeof($stack);$i++)
    {
        echo "</li>\n". display_tabs($level) ."</ul>\n";
    }
    echo "</div>";
}


function display_stack($stack)
{
	for($j=0;$j<sizeof($stack);$j++)
	{
		$disp_array = $disp_array . $stack[$j] . ",";
	}
	return $disp_array;
}

function display_tabs($num)
{
	$str_tabs = "";
	
	if($num != 0)
	{
		for($tab=1;$tab<=$num;$tab++)
		{
			$str_tabs = $str_tabs . "\t";
		}
	}
	
	return $str_tabs;
}

?>           
			</div>
		</div>
	</div>
</div>		

        <!-- Footer -->
    <div class="container">        
        <footer>
            <div class="row">
                <div class="col-lg-12 text-right">
                    <p>Copyright &copy; Sriranga Digital</p>
                </div>
            </div>
        </footer>
	</div>
	
    <!-- /.container -->

    <!-- jQuery -->
    <script src="../js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
	<script src="../js/treeview.js"></script>

</body>

</html>
