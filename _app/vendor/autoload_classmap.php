<?php

return array_merge_recursive(
	// include 3rd-party library that using generated autoload_classmap.php
	//include __DIR__ .DS. 'Datasecurity'.DS.'autoload_classmap.php',
	
	array (
		'ClassmapSingle'   => __DIR__ .DS. 'xSampleClassmapSingle' .DS. 'ClassmapSingle.php',
		/*
		 *  you can always use this class simply as:
		 *  	new ClassName()
		 *  if using any namespace in your code, (i.e. [php] namespace Something/Other [/php])
		 *  	new \ClassName();
		 */
	)
);

