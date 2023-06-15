<?php
function addTwigExtensionRecursive($directory) {
	if ($handle = opendir($directory)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != '.' && $file != '..') {
				$filePath = $directory . '/' . $file;

				if (is_dir($filePath)) {
					addTwigExtensionRecursive($filePath);
				} else {
					$newFilePath = $directory . '/' . $file . '.twig';

					if (rename($filePath, $newFilePath)) {
						echo "Renamed '$file' to '$file.twig'.<br>";
					} else {
						echo "Failed to rename '$file'.<br>";
					}
				}
			}
		}
		closedir($handle);
	}
}

$directory = './src/templates/default/plugin-name'; // Replace with the actual directory path
addTwigExtensionRecursive($directory);
?>