<?php

//A clean, simple index.php for projects

//Created by Ben Gardiner
//20-03-2020

//Make sure that 'open_basedir' in 'php.ini' is set to this location only if
//you really don't want people looking in forbidden directories.

error_reporting(0);

//Get requested directory
$currentdir = $_GET['dir'] ?? '.';

//Ignore any users trying to escape the current directory...
if (strpos($currentdir, '/..') !== false) {
	$currentdir = substr($currentdir, 0, strpos($currentdir, '/..'));
}

$currentdir_array = scandir($currentdir);

//Remove the 'this directory' and 'previous directory' items
array_shift($currentdir_array);
array_shift($currentdir_array);

$title_dir = 'Index of:<br><b>'.substr($currentdir, 1).'/</b>';

$sorteddata = array();

//Display a list with every item in the given directory
foreach ($currentdir_array as $i) {

    if ($i == 'index.php') {
        //Ignore case of index.php in main directory
        if ($currentdir == '.') {
            continue;
        //Redirect to index.php if not in main directory
        } else {
            header("Location: ".$currentdir."/index.php");
            exit();
        }
    }
        

	//Display directory
	if (is_dir($currentdir.'/'.$i)) {
		$dir =  $currentdir.'/'.$i;
		array_unshift($sorteddata, '<a href="./index.php?dir='.$dir.'"><li class="dirItem">'.$i.'</li></a>');

	//Display file
	} elseif (is_file($currentdir.'/'.$i)) {
		$filelocation = $currentdir.'/';
		array_push($sorteddata, '<a href="'.$filelocation.$i.'"><li class="fileItem">'.$i.'</li></a>');
	}
}

//If the user is trying to access forbidden locations...
if (strpos($currentdir, '.') === false) {
	$title_dir = 'Theres nothing to see here...';

	//If the user has reached the top of the file hierachy
} elseif ($currentdir == '.') {
	$prevdir = '<a><li class="choosedirItem"><b>[Choose a directory]</b></li></a>';
	array_unshift($sorteddata, $prevdir);

	//Re-add the 'previous directory' item to the top of the sorted array
} else {
	$dir = dirname($currentdir, 1);
	$prevdir = '<a href="./index.php?dir='.$dir.'"><li class="prevdirItem"><b>Up one level</b></li></a>';
	array_unshift($sorteddata, $prevdir);
}

//Create page title
$HTMLtitle = empty(substr($currentdir,1)) ? 'Index' : 'Index of '.substr($currentdir,1);

//Echo the HTML
echo '<div class="container">
	  <div class="nav_container">
	  <p>'.$title_dir.'</p>
	  <ul class="items">';

foreach ($sorteddata as $li) {
	echo $li;
}

echo '</ul>
	  </div>
	  </div>';

?>

<!DOCTYPE html>
<html>
	<head>
	<link rel="icon" href="images/favicon.ico" type="image/ico">
	<meta name="viewport" content="width=device-width">
	<title><?php echo $HTMLtitle; ?></title>
	<style>
	
	/* Reset css */
	* {
		overflow: overlay;
		margin: 0;
		padding: 0;
	}
	
	/* Main container */
	.container {
		padding: 0;
		margin: 15px;
		display: -webkit-box;
		display: -moz-box;
		display: -ms-flexbox;
		display: -webkit-flex;
		display: flex;
		justify-content: center;
		font-family: Arial;
	}
	
	/* Navigation items container */
	.nav_container {
	}
	
	/* Current dir message */
	p {
		font-size: 24px;
		margin: 5px;
		padding: 5px;
		text-align: center;
	}
	
	/* List & items */
	.items {
		display: inline-flex;
		flex-direction: column;
		padding: 0;
		list-style: none;
		width: 500px;
	}
	.items li {
		text-align: left;
		border: 1px solid black;
		padding: 5px;
		margin: 2px auto;
	}
	.items a {
		text-decoration: none;
		padding: 0px;
		margin: 0px auto;
		width: 100%;
	}
	.items .dirItem {
		background-color: rgb(230, 230, 230);
	}
	.items .fileItem {
		background-color: rgb(255, 255, 255);
	}
	.items .choosedirItem {
		background-color: rgb(185, 185, 185);
		color: black;
		text-align: center;
	}
	.items .prevdirItem {
		background-color: rgb(185, 185, 185);
		color: blue;
		text-align: center;
	}

    /* For screens <= 632px */
    @media screen and (max-width:632px) {
        .container {
            width: 100%;
            padding: 0;
            margin: 0;
            font-size: 20px;
        }
        .nav_container {
            width: 100%;
            margin: 5px 15px;
        }
        .items {
    		width: 100%;
    	}
        .items a li {
            text-align: left;
            padding: 15px 10px;
        }
        .items .choosedirItem {
            padding: 5px;
	    }
	    .items .prevdirItem {
            padding: 5px;
	    }
    }
	
	</style>
	</head>
</html>
