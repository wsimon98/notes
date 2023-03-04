<?php
if(isset($_POST["note_title"]) && isset($_POST["note_content"])) {
    $title = trim($_POST["note_title"]);
    $content = trim($_POST["note_content"]);

    if($title == "") {
        echo "Note title cannot be empty.";
        return;
    }

    if($content == "") {
        echo "Note content cannot be empty.";
        return;
    }

    $note = $title . "|" . $content . "\n";
    $file = fopen("notes.txt", "a");
    fwrite($file, $note);
    fclose($file);

    // Redirect back to the notes page
    header("Location: notes.php");
    exit();
} else {
    echo "Invalid request.";
}
?>
