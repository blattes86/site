<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">                                                                                                                                                                         <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
	<title></title>
	<meta name="author" content="blattes86"/>

	<meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
	<meta http-equiv="Content-Language" content="fr"/>
	<meta name="keywords" lang="fr" content=""/>

	<link rel="stylesheet" type="text/css" href="css/style.css"/>
	<script type="text/javascript" src="admin/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="css/menu.js"></script>
</head>

<body>
	<div id="tete">
		<h1>Bienvenue dans l'antre de la blatte</h1>
	</div>
	<div id="page">
		<div id="menudiv">
			<ul id="menu">
				<li>
					<a href="./index.php">acceuil</a>
				</li>
				<?php
				error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
					function mkmap($dir)
					{
						$folder = opendir ($dir);
						while ($file = readdir ($folder))
						{   
							if ($file != "." && $file != ".." && $file != "css" && $file != "index.php" && $file != 'admin' && $file != "acceuil.php" && $file != "robots.txt") 
							{           
								$pathfile = $dir.'/'.$file;
								/*echo ' chemin : '.$pathfile;*/
								if (is_dir($file))
								{
									$dossier = $file ;
									echo '
				<li>
					<span class="test">'.str_replace('_', ' ', $dossier).'</span>
					<ul>';
									if(filetype($pathfile) == 'dir')
								{               
									mkmap($pathfile);               
								}  
									echo '
					</ul>
				</li>
				';
								}
								else
								{
									echo '
				<li>
							<a href="./index.php?arbo='.$pathfile.'">'.str_replace('_', ' ', str_replace('.php', '', $file)).'</a>
				</li>';
								}
								
								/*
								if(filetype($pathfile) == 'dir')
								{               
									mkmap($pathfile);               
								}*/           
							}       
						}
						closedir ($folder);    

					}
					mkmap('.');
				?>
			
			</ul>
		</div>
		<div id="total">
		<?php
			
			if (empty($_GET['arbo']))
			{
				include ("acceuil.php");
			}
			else if (isset($_GET['arbo']))
			{
			$arbo=$_GET['arbo'];
			/* fonction création sommaire et réécriture page*/
			function add_anchor($string)
{
    $array = explode(" ",$string);
    $id = 0;
    for($i=0;$i<count($array);$i++)
    {
        for($t = 1;$t < 10;$t++)
        {
            $array[$i] = str_replace("</h$t>","<a name=\"title-$id\"></a></h$t>",$array[$i],$count);
            if($count > 0)
            {
                $id += $count;
            }
        }
    }
    $return = implode(" ",$array);
    return $return;
}

function makeTableOfContents($file)
{
    echo "\t<ol>\n";
    $tableau = preg_split("/[\n]+/", $file);
    $pattern='<h[0-9]*>.*</h[0-9]*>';
    $anchor='<a name=*';
    $oldN = 1;
   
    while(list($cle,$str) = each($tableau))
    {
       
        if (eregi($pattern, $str))
        {
            $chars = preg_split('/<h/', $str, -1, PREG_SPLIT_OFFSET_CAPTURE);
           
            $fullN = $chars[1][0];
            $n = substr($fullN, 0, 1);
           
            $fullTitle = substr($fullN, 2);
            $closeTag = '/</';//.$n.'>/';
            $titleSplit = preg_split($closeTag, $fullTitle, -1, PREG_SPLIT_OFFSET_CAPTURE);
            $title = substr($fullTitle, 0, ($titleSplit[1][1]-1));
           
            if (eregi($anchor, $str))
            {
                $charsanchor = preg_split('/name="/', $str, -1);
                $charsanchor2 = split("<",$charsanchor[1]);
                $title = '<a href="#'. $charsanchor2[0] .''. $title .'</a>';
            }
           
            if($n >= 1)
            {
                switch($n)
                {
                    case ($n > $oldN):
                        echo "\n\t<ol>\n";
                        echo "\t<li>".$title;
                    break;
                   
                    case ($n < $oldN):
                        echo "</li>\n";
                        $diff = $oldN - $n;
                        for($i=0;$i<$diff;$i++)
                        {
                            echo "\t</li>\n";
                            echo "\t</ol>\n";
                        }
                        echo "\t<li>".$title;
                    break;
                   
                    case ($n == $oldN):
                        echo "</li>\n";
                        echo "\t<li>".$title;
                    break;
                }
                $oldN = $n;
            }
        }
    }
    echo "\n\t</ol>\n";
}
$newtexte = add_anchor(file_get_contents($arbo));
			/*fin des fonctions*/
			echo'
		<div id="sommaire">';
makeTableOfContents($newtexte);

		echo '</div>
		<div id="contenu">';
		echo $newtexte;
			}
		echo '
		</div>';
		?>
		</div>
	</div>
</body>
</html>