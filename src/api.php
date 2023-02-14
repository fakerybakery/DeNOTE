<?php
include 'init.php';
header('Content-type: text/json');
$rq = $_REQUEST;
$r = [];
if ($delete_old_notes) {
    $conn->query('DELETE FROM note WHERE datecreated < now() - INTERVAL 30 DAY');
}
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
if ($json_api_enabled) {
    if (empty($rq['method'])) {
        $r = [
            'status' => 400,
            'message' => '400 Bad Request! Please refer to the API documentation at https://github.com/fakerybakery/DeNOTE/wiki/API-Docs.'
        ];
    } else {
        $method = trim(strtolower($rq['method']));
        if ($method == 'newnote') {
            if (!empty($rq['note']) && !empty($rq['password']) && !empty(trim($rq['note'])) && !empty(trim($rq['password']))) {
                $note = trim($rq['note']);
                $password = trim($rq['password']);
                if (strlen($password) < 4) {
                    $r = [
                        'status' => 405,
                        'message' => '405 Password Too Short! Please refer to the API documentation at https://github.com/fakerybakery/DeNOTE/wiki/API-Docs.'
                    ];
                } else {
                    # Really create note
                    $noteid = getNoteID();
                    $stmt = $conn->prepare('INSERT INTO note (note, noteid) VALUES (?, ?)');
                    $note = openssl_encrypt($note,"AES-256-CBC",$password,0,"$iv");
                    $stmt->bind_param('ss', $note, $noteid);
                    $stmt->execute();
                    $r = [
                        'status' => 201,
                        'message' => 'Note created',
                        'note_url' => htmlspecialchars($base_url . $noteid),
                        'note_id' => $noteid
                    ];
                }
                # Create Note
            } else {
                $r = [
                    'status' => 400,
                    'message' => '400 Bad Request! Please refer to the API documentation at https://github.com/fakerybakery/DeNOTE/wiki/API-Docs.'
                ];
            }
        } elseif ($method == 'viewnote') {
            if (!empty($rq['noteid']) && !empty($rq['password']) && !empty(trim($rq['noteid'])) && !empty(trim($rq['password']))) {
                $noteid = trim($rq['noteid']);
                $password = trim($rq['password']);
                if (!IDExists($noteid)) {
                    $r = [
                        'status' => 404,
                        'message' => '404 Note Not Found! Please refer to the API documentation at https://github.com/fakerybakery/DeNOTE/wiki/API-Docs.'
                    ];
                } else {
                    # Decrypt + Destroy Note
                    $stmtcheck = $conn->prepare('SELECT * FROM note WHERE noteid = ?');
                    $stmtcheck->bind_param('s', $noteid);
                    $stmtcheck->execute();
                    $rescheck = $stmtcheck->get_result();
                    $content = mysqli_fetch_array($rescheck)['note'];
                    if ((!openssl_decrypt($content, 'AES-256-CBC', $password, 0, "$iv")) && (!openssl_decrypt($content, 'AES-256-CBC', $password))) {
                        $stmtdestroy = $conn->prepare('DELETE FROM note WHERE noteid = ?');
                        $stmtdestroy->bind_param('s', $noteid);
                        $stmtdestroy->execute();
                        // die('Incorrect Password! (This note has been delete forever due to the incorrect password. You cannot recover it.)');
            //            http_response_code(403);
                        $r = [
                            'status' => 403,
                            'message' => '403 Incorrect Password! This note has been destroyed forever! Please refer to the API documentation at https://github.com/fakerybakery/DeNOTE/wiki/API-Docs.'
                        ];
                    } else {
                        $stmtdestroy = $conn->prepare('DELETE FROM note WHERE noteid = ?');
                        $stmtdestroy->bind_param('s', $noteid);
                        $stmtdestroy->execute();
                        $decrypted = openssl_decrypt($content, 'AES-256-CBC', $password, 0, "$iv");
                        if (!$decrypted) {
                            $decrypted = openssl_decrypt($content, 'AES-256-CBC', $password);
                        }
                        $r = [
                            'status' => 200,
                            'message' => '200 Note Decryted! Please refer to the API documentation at https://github.com/fakerybakery/DeNOTE/wiki/API-Docs.',
                            'note' => $decrypted
                        ];
                    }
                }
            } else {
                $r = [
                    'status' => 400,
                    'message' => '400 Bad Request! Please refer to the API documentation at https://github.com/fakerybakery/DeNOTE/wiki/API-Docs.'
                ];
            }
        } else {
            $r = [
                'status' => 400,
                'message' => '400 Bad Request! Please refer to the API documentation at https://github.com/fakerybakery/DeNOTE/wiki/API-Docs.'
            ];
        }
    }
} else {
    $r = [
        'status' => 401,
        'message' => '401 API Disabled! Please refer to the API documentation at https://github.com/fakerybakery/DeNOTE/wiki/API-Docs.'
    ];
}
http_response_code($r['status']);
die(json_encode($r));
