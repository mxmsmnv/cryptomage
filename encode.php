<?php

$key = '098f6bcd4621d373cade4e832627b4f6';

function encryptAndRenameFile($originalFilename, $key)
{
    $fileExtension = pathinfo($originalFilename, PATHINFO_EXTENSION);

    // Generate IV with length of 16 bytes
    $iv = substr(md5($key), 0, 16);

    // Separate the base filename from the extension
    $filenameWithoutExtension = pathinfo($originalFilename, PATHINFO_FILENAME);

    // Generate a new file name using encryption for the base filename
    $encryptedName = openssl_encrypt($filenameWithoutExtension, 'aes-256-cbc', md5($key), 0, $iv);

    // Replace slashes in the filename because they are interpreted as directory delimiters
    $encryptedName = str_replace('/', '-', $encryptedName);

    $newFilename = $encryptedName . '.' . $fileExtension;

    // Rename the file
    if (rename($originalFilename, $newFilename)) {
        echo "✅ $newFilename - file has been successfully renamed\n";
    } else {
        echo "❌ Error when renaming a file: $originalFilename\n";
    }
}

// Read all files with extensions .jpg, .jpeg, and .png in the current folder
$files = glob('*.{jpg,jpeg,png}', GLOB_BRACE);

if (empty($files)) {
    echo "There are no files with the extensions .jpg, .jpeg, and .png in the current folder\n";
} else {
    foreach ($files as $file) {
        encryptAndRenameFile($file, $key);
    }
}
?>
