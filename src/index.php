<?php


$conn = mysqli_connect('localhost', 'root', 'root', 'denote');


$base_url = 'https://base-api-url/denote?id='; // Path to index.php


function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}
function IDExists($id) {
    global $conn;
    $stmtcheck = $conn->prepare('SELECT * FROM note WHERE noteid = ?');
    $stmtcheck->bind_param('s', $id);
    $stmtcheck->execute();
    $rescheck = $stmtcheck->get_result();
    return mysqli_num_rows($rescheck) != 0;
}
function getNoteID() {
    $id = generateRandomString();
    if (!IDExists($id)) {
        return $id;
    } else {
        return getNoteID();
    }
}
if (isset($_GET['id']) && !empty($_GET['id'])) {
    if (!IDExists($_GET['id'])) {
        header('Content-type: text/plain');
        die('The note you are looking for has likely been deleted. Please contact the note owner and ask them to re-create the note.');
    }
    if (!empty($_POST['password'])) {
        header('Content-type: text/plain');
        global $conn;
        $stmtcheck = $conn->prepare('SELECT * FROM note WHERE noteid = ?');
        $stmtcheck->bind_param('s', $_GET['id']);
        $stmtcheck->execute();
        $rescheck = $stmtcheck->get_result();
        $content = mysqli_fetch_array($rescheck)['note'];
        if (!openssl_decrypt($content, 'AES-256-CBC', $_POST['password'])) {
            $stmtdestroy = $conn->prepare('DELETE FROM note WHERE noteid = ?');
            $stmtdestroy->bind_param('s', $_GET['id']);
            $stmtdestroy->execute();
            die('Incorrect Password! (This note has been delete forever due to the incorrect password. You cannot recover it.)');
        }
        $decrypted = openssl_decrypt($content, 'AES-256-CBC', $_POST['password']);
        echo 'Here is your note: (It has been destroyed, you cannot view it again.)

';
        echo $decrypted;
        $stmtdestroy = $conn->prepare('DELETE FROM note WHERE noteid = ?');
        $stmtdestroy->bind_param('s', $_GET['id']);
        $stmtdestroy->execute();
    } else {
        echo '<h1>View Note</h1>
    <p>Password:</p>
    <form method="post">
    <input name="password">
    <button type="submit">Decrypt</button>
    <p><small>IMPORTANT: This note will be deleted after 1 incorrect try.</small></p>
    </form>';
    }
    exit;
}
if (!empty($_POST['note']) && !empty(trim($_POST['note'])) && !empty($_POST['password'])) {
    if (strlen($_POST['password']) < 4) {
        echo('Error, your password must be at least 4 char.s long!');
    } else {
        $noteid = getNoteID();
        $stmt = $conn->prepare('INSERT INTO note (note, noteid) VALUES (?, ?)');
        $note = openssl_encrypt($_POST['note'],"AES-256-CBC",$_POST['password']);
        $stmt->bind_param('ss', $note, $noteid);
        $stmt->execute();
        die('Success, your note URL is: ' . $base_url . $noteid);
    }
}
?>
<h1>Create Note</h1>
<h3>This note will self-destruct after someone views it.</h3>
<form method="post">
<p>Note:</p>
<textarea name="note" required></textarea>
<p><small>NOTE: This note will be encrypted with AES-256-CBC. If you forget the password, the note will be lost forever. The note will be destroyed on view.</small></p>
<p>Password:</p>
<input name="password" required>
<button type="submit">Create</button>
</form>
