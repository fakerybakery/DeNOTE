# DeNOTE
Welcome to DeNOTE, the private encrypted one-view note-taking platform. DeNOTE is still in beta, so our UI is not very good :(.

WHAT IT DOES: DeNOTE is an encrypted, open-sourced, ~~zero-knowledge~~ no-data-stored<sup>1</sup> note taking platform. You input a note and password. You get a shareable link. The note is deleted when someone either successfully decrypts the note or gets the password wrong. There is zero-tolerance for getting the password wrong - the note will just be deleted forever.

You can get the code in `src/index.php`. Make sure to run `INSTALL.sql` in your MySQL server and modify `src/index.php` to fit your needs.

All code &copy; 2022 mrfakename. All rights reserved.


<sub><sup><sup>1</sup>As an anonymous observer pointed out, if DeNOTE is decrypted on the server side, it is not truly zero-knowledge. However it is open-sourced and you can run it on your own server.</sup></sub>
