<?php
if(isset($_POST['note_title'])) {
    $noteTitle = $_POST['note_title'];
    $notes = file_get_contents("notes.txt");
    $lines = explode("\n", $notes);
    $newNotes = '';
    foreach ($lines as $line) {
        if(strpos($line, $noteTitle) === false) {
            $newNotes .= $line . "\n";
        }
    }
    file_put_contents("notes.txt", $newNotes);
}

header("Location: notes.php");
exit();
?>
