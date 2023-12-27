<?php

$key = '098f6bcd4621d373cade4e832627b4f6';

function decryptAndRenameFile($encryptedFilename, $key)
{
    $fileExtension = pathinfo($encryptedFilename, PATHINFO_EXTENSION);

    // Get rid of the file extension and return slashes
    $encryptedName = str_replace('.' . $fileExtension, '', $encryptedFilename);
    $encryptedName = str_replace('-', '/', $encryptedName);

    // Generate IV with length of 16 bytes
    $iv = substr(md5($key), 0, 16);

    // Decode the file name
    $originalFilename = openssl_decrypt($encryptedName, 'aes-256-cbc', md5($key), 0, $iv);

    // Rename the file
    if (rename($encryptedFilename, $originalFilename)) {
        echo "✅ $originalFilename - file has been successfully renamed\n";
    } else {
        echo "❌ Error when renaming a file: $encryptedFilename\n";
    }
}

// Read all files with extensions .jpg, .jpeg and .png in the current folder
$files = glob('*.{jpg,jpeg,png}', GLOB_BRACE);

if (empty($files)) {
    echo "There are no files with the extensions .jpg, .jpeg and .png in the current folder\n";
} else {
    foreach ($files as $file) {
        decryptAndRenameFile($file, $key);
    }
}

?>