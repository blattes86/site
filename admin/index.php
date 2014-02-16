<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">                                                                                                                                                   <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
	<script type="text/javascript" src="./ckeditor/ckeditor.js"></script>
	
	<title></title>
	<meta name="author" content="blattes86"/>

	<meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
	<meta http-equiv="Content-Language" content="fr"/>
	<meta name="keywords" lang="fr" content=""/>

	<link rel="stylesheet" type="text/css" href="css/style.css"/>
	
</head>
	<body>
		<div id="tete">
		<h1>Zone admin de l'antre de la blatte</h1>
	</div>
		<div id="page">
			<div id="menu">
			<p class="">MENU PRINCIPAL</p>
				<?php
				error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
					echo '<a href="./index.php">acueil</a><br/>';
					function mkmap($dir)
					{
						$folder = opendir ($dir);
						while ($file = readdir ($folder))
						{   
							if ($file != "." && $file != ".." && $file != "admin" && $file != "css" && $file != "index.php")
							{           
								$pathfile = $dir.'/'.$file;
								if (is_dir($file))
								{
									$dossier = $file ;
									echo $dossier.'
									<br/>
									';
								}
								else
								{
								echo '<a href="./index.php?arbo='.$pathfile.'">'.$file.'</a>
								<br/>
								';
								}
								if(filetype($pathfile) == 'dir')
								{              
									mkmap($pathfile);               
								}
							}
						}
						closedir ($folder);    
					}

					mkmap('./..');
				?>
		</div>
		
		<?php
			
			
			if (empty($_GET['arbo']) && empty($_GET['action']))
			{
				echo 'créer un dossier
				<form method="post" action="index.php?action=creat_dir">
				<p>
					<input type="text" name="dir" id="dir" />
					<input type="submit" value="Enregistrer" />
				</p>
				</form>
					
				créer un fichier
				<form method="post" action="index.php?action=creat_file">
					<p>
				<select name="dir">';
				$indir = scandir('./..');
				$indir = array_diff($indir, array('.', '..', 'admin', 'css', 'acceuil.php', 'index.php'));
				foreach ($indir AS $dossier)
				{
					echo '<option value="'.$dossier.'">'.$dossier.'</option>';
				}

				echo "</select>
					<input type='text' name='file' id='file' />
					<input type='submit' value='Enregistrer' />
					</p>
				</form>
				<br/>";

			}
			else if (isset($_GET['action']))
			{
				$action=$_GET['action'];
				if ($action == 'creat_dir')
				{
					$dir=($_POST['dir']);
					if (!mkdir('./../'.$dir.'/', 0777, true)) 
					{
						die('Echec lors de la création du dossier '.$dir);
					}
					else
					{ 
						echo 'le dossier '.$dir.' a bien été créé';
					}
				}
				else if ($action == 'creat_file')
				{
					echo 'création fichier';
					$dir=($_POST['dir']);
					$file=($_POST['file']);
					if (touch('./../'.$dir.'/'.$file))
					{
						echo 'le fichier '.$file. 'a été créé dans le dossier '.$dir;
					}
					else
					{
						echo 'le fichier '.$file. 'n\'a pas été créé dans le dossier '.$dir;
					}
				}
				else if ($action == 'sav_page')
				{
					if (isset($_POST['contenu']))
					{
						if (!file_put_contents($_POST['page'], $_POST['contenu']))
						{
							die('le contenu n\'as pas été enregisté.<br/>');
						}
						else
						{
							echo 'contenu enregistré';
						}
					}
				}
			}
			else if (isset($_GET['arbo']))
			{
				$arbo=$_GET['arbo'];
				echo '
				<form action="index.php?action=sav_page" method="post" target="_top">
					<input type="hidden" name="page" value="'.$arbo.'" >
					<textarea name="contenu" rows="10" cols="50" >';
					echo file_get_contents($arbo);
					echo'</textarea>
					<script type="text/javascript">
						CKEDITOR.replace( "contenu" );
					</script>
				<input type="submit" value="Sauver le contenu">
				</form><br/>';
			}

		?>
	</div>
</body>
</html>