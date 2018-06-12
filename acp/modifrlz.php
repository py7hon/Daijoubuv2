<?php
	if(!defined("ACPPAGE")) exit();
	$fansuberName	= (isset($_GET['fansuber'])) ? mysql_real_escape_string($_GET['fansuber']) : '';	
	
	$token = generate_token("edit");
?>
	<h1><span class="Style1">:: Edit <?php echo $config['accro']; ?> &#1103;eleases ::<br /><br /></span><?php if($fansuberName != '') echo ' from ' . htmlentities($fansuberName); ?></h1>
	<p>Total releases :
	<?php
		$donnees = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS nb_entry FROM releases"));
		echo $donnees['nb_entry'];
		$totalCracks = $donnees['nb_entry'];

		if($fansuberName != '')
		{
			$r = mysql_query("SELECT COUNT(*) AS nb_entry FROM releases WHERE fansuber='" . $fansuberName . "'");
			$donnees = mysql_fetch_array($r);
			echo '<br />Total releases of <b>' . htmlentities($fansuberName) . '</b>: ' . $donnees['nb_entry'];
		}
	?></p>
<center><?php
	$page = (isset($_GET['spg'])) ? (int)$_GET['spg'] : 1;
	$page = $page < 0 ? 0 : $page;
	
	if($page != 0) $premierMessageAafficher = ($page - 1) * $config['cracksparpage'];
	
	$nombreDePages  = ceil($totalCracks / $config['cracksparpage']);
	
	echo '<span id="pagenums">Page : ';
	for ($i = 1 ; $i <= $nombreDePages ; $i++)
	{
		if($i != $page) echo '<a href="' . $_SERVER['SCRIPT_NAME'] . '?crk=modifrlz&spg=' . $i . '">' . $i . '</a>';
		else echo $i . ' ';
	}
	echo '</span>';

?></center>
	<hr />
	<form>
	<table border="0" cellpadding="3" cellspacing="0">
		<tr><td><center>Release name</center></td><td><center>url</center></td><td>Date (m/d/Y)</td><td>Cracker</td>
		<td>Save</td>
		</tr>
<?php
	if($fansuberName != '')
		$r = mysql_query("SELECT * FROM releases WHERE fansuber='" . $fansuberName . "' ORDER BY date DESC");
	elseif($page == 0)
		$r = mysql_query("SELECT * FROM releases");
	else
		$r = mysql_query("SELECT * FROM releases ORDER BY date DESC LIMIT $premierMessageAafficher, {$config['cracksparpage']}");

	while($donnees = mysql_fetch_array($r) )
	{
		echo '<input type="hidden" id="token" value="'.$token.'"/>';
		echo "\n<tr>";
			echo '<td><input id="name' , $donnees['id'] , '" class="acpinput" type="text" value="' , htmlentities($donnees['name']) , '" onkeypress="change(' , $donnees['id'] , ');" /></td>';
			echo '<td><input id="url' , $donnees['id'] , '" class="acpinput" type="text" value="' ,  htmlentities($donnees['url']) , '" onkeypress="change(' , $donnees['id'] , ');" /></td>';
			echo '<td>' , date('m/d/Y', $donnees['date']) , '</td>';
			echo '<td><input id="fansuber' , $donnees['id'] , '" class="acpinput" type="text" value="' , htmlentities($donnees['fansuber']) , '" onkeypress="change(' , $donnees['id'] , ');" /></td>';
			echo '<td><span id="update' , $donnees['id'] , '" style="display:none"><input type="button" value="save" id="button' , $donnees['id'] , '" onclick="saverow(' , $donnees['id'] , '); return false;" /></span></td>';
		echo '</tr> <!-- ' , $donnees['id'] , ' -->'; 
	}
?>
	</table>
	</form>
<hr />
