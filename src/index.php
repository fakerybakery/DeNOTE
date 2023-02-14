<?php

# Config moved to init.php

if ($delete_old_notes) {
    $conn->query('DELETE FROM note WHERE datecreated < now() - INTERVAL 30 DAY');
}

$version = '0.4-beta';

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
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
        // header('Content-type: text/plain');
        http_response_code(404);
        // die('The note you are looking for has likely been deleted. Please contact the note owner and ask them to re-create the note.');
        ?>
                    <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Note Not Found - DeNOTE</title>
    <style>
 
      @import url('b.css');
    </style>
  </head>

  <body class="card p-5 m-5">
    <h2>DeNOTE</h2>
    <h1>Note Not Found</h1>
    <h3>The note you're looking for has either been deleted or has not been created.</h3>
    <a href="<?=htmlspecialchars($base_url)?>" class="btn btn-success">DeNOTE Homepage</a>
    <p>Encrypted with AES-256-CBC.</p>
    <p>&copy; 2022 The DeNOTE Project. All rights reserved. DeNOTE does not endorse or create user-generated notes. <a href="https://github.com/DeNOTE-Project/DeNOTE/">Check out DeNOTE on GitHub</a>. DeNOTE version <?=$version?>.</p>
  </body>

</html>
        <?php
        exit;
    }
    if (!empty($_POST['password'])) {
        global $conn;
        $stmtcheck = $conn->prepare('SELECT * FROM note WHERE noteid = ?');
        $stmtcheck->bind_param('s', $_GET['id']);
        $stmtcheck->execute();
        $rescheck = $stmtcheck->get_result();
        $content = mysqli_fetch_array($rescheck)['note'];
        if ((!openssl_decrypt($content, 'AES-256-CBC', $_POST['password'], 0, "$iv")) && (!openssl_decrypt($content, 'AES-256-CBC', $_POST['password']))) {
            $stmtdestroy = $conn->prepare('DELETE FROM note WHERE noteid = ?');
            $stmtdestroy->bind_param('s', $_GET['id']);
            $stmtdestroy->execute();
            // die('Incorrect Password! (This note has been delete forever due to the incorrect password. You cannot recover it.)');
            http_response_code(403);
            ?>
            <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Incorrect Password - DeNOTE</title>
    <style>
 
      @import url('b.css');
    </style>
  </head>

  <body class="card p-5 m-5">
    <h2>DeNOTE</h2>
    <h1>Incorrect Password</h1>
    <h3>The password you have entered is incorrect. The note has been destroyed.</h3>
    <p>Sorry, you made a typo or got the password wrong on this note. Now it's gone. Forever.</p>
    <a href="<?=htmlspecialchars($base_url)?>" class="btn btn-success">DeNOTE Homepage</a>
    <p>Encrypted with AES-256-CBC.</p>
    <p>&copy; 2022 The DeNOTE Project. All rights reserved. DeNOTE does not endorse or create user-generated notes. <a href="https://github.com/DeNOTE-Project/DeNOTE/">Check out DeNOTE on GitHub</a>. DeNOTE version <?=$version?>.</p>
  </body>

</html>
            <?php
            exit;
        }
        $stmtdestroy = $conn->prepare('DELETE FROM note WHERE noteid = ?');
        $stmtdestroy->bind_param('s', $_GET['id']);
        $stmtdestroy->execute();
        $decrypted = openssl_decrypt($content, 'AES-256-CBC', $_POST['password'], 0, "$iv");
        if (!$decrypted) {
          $decrypted = openssl_decrypt($content, 'AES-256-CBC', $_POST['password']);
        }
//         echo 'Here is your note: (It has been destroyed, you cannot view it again.)
?>
            <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>View Note - DeNOTE</title>
    <style>
 
      @import url('b.css');
      * {
          user-select: none;
          -webkit-user-select: none;
          -moz-user-select: none;
      }
    </style>
  </head>

  <body class="card p-5 m-5">
    <h2>DeNOTE</h2>
    <h1>View Note</h1>
    <h3>Here's your note:</h3>
    <div class="card p-3"><?=nl2br(htmlspecialchars($decrypted))?></div>
    <p><b>This note has been destroyed.</b> Don't reload the page - this note will disappear. This note was encrypted using AES-256-CBC with DeNOTE.</p>
    <p>&copy; 2022 The DeNOTE Project. All rights reserved. DeNOTE does not endorse or create user-generated notes. <a href="https://github.com/DeNOTE-Project/DeNOTE/">Check out DeNOTE on GitHub</a>. DeNOTE version <?=$version?>.</p>
  </body>

</html>
<?php
// ';
        // echo $decrypted;
        exit;
    } else {
    //     echo '<h1>View Note</h1>
    // <p>Password:</p>
    // <form method="post">
    // <input name="password">
    // <button type="submit">Decrypt</button>
    // <p><small>IMPORTANT: This note will be deleted after 1 incorrect try.</small></p>
    // </form>';
    // }
    ?>
    <!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Decrypt Note - DeNOTE</title>
    <style>
 
      @import url('b.css');
    </style>
  </head>

  <body class="card p-5 m-5">
    <h2>DeNOTE</h2>
    <h1>Decrypt Note</h1>
    <h3>This note will self-destruct after being viewed or if someone gets the password wrong.</h3>
    <form method="post" onsubmit="return confirm('Are you sure you want to decrypt this note? After decryption, this note will be destroyed forever. There is no way to recover a destroyed note. Make sure you want to decrypt this note and destroy it before entering the password. If you enter the wrong password, the note will be destroyed.');">
      </div>
        <label for="password">Password:</label>
        <input type="password" name="password" placeholder="Password" class="form-control" autofocus>
      </div>
      <p></p>
      <button class="btn btn-danger" type="submit">Decrypt</button>
    </form>
    <p>Encrypted with AES-256-CBC.</p>
    <p>&copy; 2022 The DeNOTE Project. All rights reserved. DeNOTE does not endorse or create user-generated notes. <a href="https://github.com/DeNOTE-Project/DeNOTE/">Check out DeNOTE on GitHub</a>. DeNOTE version <?=$version?>.</p>
  </body>

</html>

<?php
    exit;
}
}
if (!empty($_POST['note']) && !empty(trim($_POST['note'])) && !empty($_POST['password'])) {
    if (strlen($_POST['password']) < 4) {
        echo('Error, your password must be at least 4 char.s long!');
    } else {
        $noteid = getNoteID();
        $stmt = $conn->prepare('INSERT INTO note (note, noteid) VALUES (?, ?)');
        $note = openssl_encrypt($_POST['note'],"AES-256-CBC",$_POST['password'],0,"$iv");
        $stmt->bind_param('ss', $note, $noteid);
        $stmt->execute();
        ?>
        <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DeNOTE</title>
    <style>
 
      @import url('b.css');
    </style>
  </head>

  <body class="card p-5 m-5">
    <h2>DeNOTE</h2>
    <h1>Decrypt Note</h1>
    <h3>Your note has been created!</h3>
    <p>Your note URL:</p>
    <input type="text" value="<?=htmlspecialchars($base_url . $noteid)?>" class="form-control" disabled readonly>
    <p>Encrypted with AES-256-CBC.</p>
    <p>&copy; 2022 The DeNOTE Project. All rights reserved. DeNOTE does not endorse or create user-generated notes. <a href="https://github.com/DeNOTE-Project/DeNOTE/">Check out DeNOTE on GitHub</a>. DeNOTE version <?=$version?>.</p>
  </body>

</html>
        <?php
        exit;
        // die('Success, your note URL is: ' . $base_url . $noteid);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DeNOTE</title>
    <style>
 
      @import url('b.css');
    </style>
  </head>

  <body class="card p-5 m-5">
    <h2>DeNOTE</h2>
    <h1>New Encrypted Note</h1>
    <h3>This note will self-destruct after being viewed or if someone gets the password wrong.</h3>
    <form method="post">
      <div class="form-group">
        <label for="note">Note:</label>
        <textarea name="note" id="note" class="form-control" placeholder="Write your note here..." autofocus></textarea>
      <div class="form-group">
      </div>
        <label for="password">Password:</label>
        <input type="password" name="password" placeholder="Password" class="form-control">
      </div>
      <p></p>
      <button class="btn btn-primary" type="submit">Create Note</button>
    </form>
    <p>Encrypted with AES-256-CBC.</p>
    <p>&copy; 2022 The DeNOTE Project. All rights reserved. DeNOTE does not endorse or create user-generated notes. <a href="https://github.com/DeNOTE-Project/DeNOTE/">Check out DeNOTE on GitHub</a>. DeNOTE version <?=$version?>.</p>
  </body>

</html>
