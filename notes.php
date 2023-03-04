<!DOCTYPE html>
<html>
<head>
    <title>Notes</title>
    <style>
        body {
            background-color: #222;
            color: #ddd;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            flex: 1;
            padding: 20px;
            background-color: #333;
        }

        .sidebar h2 {
            margin-top: 0;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .main-content {
            flex: 3;
            padding: 20px;
        }

        .main-content h2 {
            margin-top: 0;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type=text], textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #444;
            color: #ddd;
            margin-bottom: 10px;
        }

        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type=submit]:hover {
            background-color: #3e8e41;
        }

        #note-preview {
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #444;
            max-height: 300px;
            overflow-y: auto;
        }

        .folder {
            font-weight: bold;
            cursor: pointer;
        }

        .folder ul {
            list-style: none;
            padding-left: 20px;
            margin: 0;
        }

        .folder ul li {
            margin-bottom: 5px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Notes Index</h2>
            <ul id="note-index">
                <?php
                    $notes = array();
                    $file = fopen("notes.txt", "r");
                    if($file) {
                        while(($line = fgets($file)) !== false) {
                            $line = trim($line);
                            if($line == "") continue;
                            $parts = explode("|", $line);
                            $title = trim($parts[0]);
                            $content = trim($parts[1]);
                            $folders = explode("/", $title);
                            $folderIndex = 0;
                            $currentFolder = &$notes;
                            while($folderIndex < count($folders) - 1) {
                                $folderName = trim($folders[$folderIndex]);
                                if(!isset($currentFolder[$folderName])) {
                                    $currentFolder[$folderName] = array();
                                }
                                $currentFolder = &$currentFolder[$folderName];
                                $folderIndex++;
                            }
                            $noteName = trim($folders[$folderIndex]);
                                                        if(!isset($currentFolder[$noteName])) {
$currentFolder[$noteName] = $content;
                            }
                        }
                        fclose($file);
                    }

                    function displayNotes($notes, $element) {
                        echo '<ul>';
                        foreach($notes as $key => $value) {
                            if(is_array($value)) { // Folder
                                echo '<li class="folder">' . htmlspecialchars($key) . '<ul class="folder-notes">';
                                displayNotes($value, $element);
                                echo '</ul></li>';
                            } else { // Note
                                echo '<li><a href="#" onclick="document.getElementById(\'note-preview\').innerHTML=\'' . htmlspecialchars($value) . '\'">' . htmlspecialchars($key) . '</a>';
                                echo '<form method="post" action="delete_note.php" style="display: inline-block; margin-left: 10px;">';
                                echo '<input type="hidden" name="note_title" value="' . htmlspecialchars($key) . '">';
                                echo '<button type="submit" onclick="return confirm(\'Are you sure you want to delete this note?\')"><span>Delete</span></button>';
                                echo '</form></li>';
                            }
                        }
                        echo '</ul>';
                    }

                    displayNotes($notes, "");
                ?>
            </ul>
        </div>
        <div class="main-content">
            <h2>Write a Note</h2>
            <form action="save_note.php" method="post">
                <label for="note-title">Note Title:</label>
                <input type="text" id="note-title" name="note_title" placeholder="Enter a title for your note...">
                <label for="note-content">Note:</label>
                <textarea id="note-content" name="note_content" placeholder="Write your note here..."></textarea>
                <input type="submit" value="Save Note">
            </form>
            <h2>Note Preview</h2>
            <div id="note-preview"></div>
        </div>
    </div>
    <script>
        window.onload = function() {
            let notePreview = document.getElementById("note-preview");
            notePreview.innerHTML = "";
            <?php
                $notes = array();
                $file = fopen("notes.txt", "r");
                if($file) {
                    while(($line = fgets($file)) !== false) {
                        $line = trim($line);
                        if($line == "") continue;
                        $parts = explode("|", $line);
                        $title = trim($parts[0]);
                        $content = trim($parts[1]);
                        $folders = explode("/", $title);
                        $folderIndex = 0;
                        $currentFolder = &$notes;
                        while($folderIndex < count($folders) - 1) {
                            $folderName = trim($folders[$folderIndex]);
                            if(!isset($currentFolder[$folderName])) {
                                $currentFolder[$folderName] = array();
                            }
                            $currentFolder = &$currentFolder[$folderName];
                            $folderIndex++;
                        }
                        $noteName = trim($folders[$folderIndex]);
                        if(!isset($currentFolder[$noteName])) {
                            $currentFolder[$noteName] = $content;
                        }
                    }
                    fclose($file);
                }
                echo "displayNotes(" . json_encode($notes) . ", notePreview);";
            ?>
        }
    </script>
</body>
</html>
