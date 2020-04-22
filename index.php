<?php

//A simple index.php for projects

//Created by Ben Gardiner
//20-03-2020

//Make sure that 'open_basedir' in 'php.ini' is set to this location only if
//you really don't want people looking in forbidden directories.

error_reporting(0);

//Get requested directory
$currentdir = $_GET['dir'] ? : '.';
$currentdir = urldecode($currentdir);

//Ignore any users trying to escape the current directory...
if (strpos($currentdir, '..') !== false) {
	$currentdir = substr($currentdir, 0, strpos($currentdir, '/..'));
}

$currentdir_array = scandir($currentdir);

//Remove the 'this directory' and 'previous directory' items
array_shift($currentdir_array);
array_shift($currentdir_array);

$title_dir = '<span id="currentdir_title">Index of:</span><span id="currentdir">'.substr($currentdir, 1).'/</span>';

$sorteddata = array();

//Display a list with every item in the given directory
foreach ($currentdir_array as $i) {

    if ($i == 'index.php') {
        //Ignore case of index.php in main directory
        if ($currentdir == '.') {
            continue;
        //Redirect to index.php if not in main directory
        // } else {
            // header("Location: ".$currentdir."/index.php");
            // exit();
        }
    }
        

	//Display directory
	if (is_dir($currentdir.'/'.$i)) {
		$dir =  urlencode($currentdir.'/'.$i);
		$ext = '';
		$diritem = '<a href="./index.php?dir='.$dir.'"><li class="dirItem"><span id="title">'.$i.'</span><span id="ext">'.$ext.'</span></li></a>';
		array_unshift($sorteddata, $diritem);

	//Display file
	} elseif (is_file($currentdir.'/'.$i)) {
		$file = $currentdir.'/'.$i;
		$ext = strtoupper(pathinfo($i, PATHINFO_EXTENSION)).' File';
		$fileitem = '<a href="'.$file.'"><li class="fileItem"><span id="title">'.$i.'</span><span id="ext">'.$ext.'</span></li></a>';
		array_push($sorteddata, $fileitem);
	}
}

//If the user is trying to access forbidden locations...
if (strpos($currentdir, '.') === false) {
	$title_dir = 'Theres nothing to see here...';

	//If the user has reached the top of the file hierachy
} elseif ($currentdir == '.') {
	$ext = '';
	$choosedirItem = '<a><li class="choosedirItem"><span id="title">Choose a directory or file</span><span id="ext">'.$ext.'</span></li></a>';
	array_unshift($sorteddata, $choosedirItem);

	//Add the root and parent directories to the top of the sorted array
} else {
	$dir = urlencode(dirname($currentdir, 1));
	$ext_prevdir = '';
	$ext_homedir = '';
    $prevdirItem = '<a href="./index.php?dir='.$dir.'"><li class="prevdirItem"><span id="title">Parent directory (Up one level)</span><span id="ext">'.$ext_prevdir.'</span></li></a>';
    $homedirItem = '<a href="./index.php?dir=."><li class="homedirItem"><span id="title">Root directory (Home)</span><span id="ext">'.$ext_homedir.'</span></li></a>';
    array_unshift($sorteddata, $homedirItem, $prevdirItem);
}

//Create page title
$HTMLtitle = empty(substr($currentdir,1)) ? 'Index' : 'Index of '.substr($currentdir,1);

//Echo the HTML
echo '<div class="container">
	  <div class="nav_container">
	  <p class="title">'.$title_dir.'</p>
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
	   margin: 0;
	   padding: 0;
	   text-decoration: none;
	   font-size: 20px;
	}
	
	/* Set scrollbar style */
	::-webkit-scrollbar {
        height: 4px;
    }
    ::-webkit-scrollbar-track-piece {
        background: #e8e8e8;
    }
    ::-webkit-scrollbar-thumb {
        background: #777;
    }
	
	/* Main container */
	.container {
		padding: 0;
		margin: 15px auto;
		font-family: Arial;
		max-width: 700px;
		overflow: overlay;
	}
	
	/* Navigation items container */
	.nav_container {
	}
	
	/* Current dir message */
	.title {
	   display: flex;
	   flex-direction: column;
	   text-align: center;
	   justify-content: center;
	   margin-bottom: 10px;
	}
	
	.title #currentdir_title {
		font-size: 24px;
		text-align: center;
	}
	.title #currentdir {
		font-size: 24px;
		padding-bottom: 5px;
		text-align: center;
  overflow: overlay;
  white-space: nowrap;
	}
	
	/* List & items */
	.items {
		display: inline-flex;
		flex-direction: column;
		padding: 0;
		list-style: none;
		width: 700px;
	}
	.items li {
	    display: flex;
	    flex-direction: column;
		padding: 5px;
		margin: 2px auto;
  overflow: overlay;
	}​
	.items a {
		padding: 0px;
		margin: 0px auto;
		width: 100%;
	}
	
	#title {
        display: flex;
        flex-direction: row;
        margin-right: auto;
	}
	#ext {
        display: flex;
        flex-direction: row;
        color: black;
        margin-right: auto;
	}

	.dirItem {
		background-color: rgba(0,0,0,0.22);
	}
	.fileItem {
		background-color: rgba(0,0,0,0.08);
		color: rgb(0,10,170);
	}
	.choosedirItem {
		background-color: rgba(50, 120, 200, 0.6);
		color: black;
	}
	.prevdirItem {
		background-color: rgba(95, 185, 185, 0.6);
		color: rgba(20,0,200,0.8);
	}
    .homedirItem {
		background-color: rgba(50, 120, 200, 0.6);
		color: rgba(20,0,200,0.8);
	}
    .choosedirItem #title, .prevdirItem #title, .homedirItem #title {
        margin-left: auto;
        margin-right: auto;
	}

    /* For screens <= 632px */
    @media screen and (max-width:720px) {
        * {
            overflow: auto;
            white-space: nowrap;
        }
        body {
            margin: 10px;
        }
        .container {
            margin: 0;
            width: 100%;
            padding: 0;
            font-size: 20px;
        }
        .nav_container {
            width: 100%;
            margin: 0;
        }
        .title {
            margin-bottom: 5px;
        }
        .items {
    		width: 100%;
    	}
        .items a li {
            align-content: space-between;
        }
    }
	</style>
	</head>
</html>
