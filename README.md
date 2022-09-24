# DeNOTE
Welcome to DeNOTE, the private encrypted one-view note-taking platform. DeNOTE is still in beta, so our UI is not very good :(.

WHAT IT DOES: DeNOTE is an encrypted, open-sourced, ~~zero-knowledge~~ no-data-stored<sup>1</sup> note taking platform. You input a note and password. You get a shareable link. The note is deleted when someone either successfully decrypts the note or gets the password wrong. There is zero-tolerance for getting the password wrong - the note will just be deleted forever.

You can get the code in `src/index.php`. Make sure to run `INSTALL.sql` in your MySQL server and modify `src/index.php` to fit your needs.

## FAQ

**Q: Why delete it (forever) after one incorrect try?**

A: I don't want brute force attacks. I could let someone try 5 or 10 times, but if they have a list of commonly used passwords, it would be more secure to only allow it _once_.

**Q: How is this more secure than another private encrypted note taking platform?**
A:

1. It destroys itself when you read it or when you get the password wrong.
2. It is open sourced.
3. You host it yourself!

## How to report a security vulnerability

1. Please, email me! My email is me [ at ] mrfake [ dot ] name!
2. Just in case I didn't get the email, submit a GH issue! (Don't disclose the whole vulnerability in the issue or it will be public to everyone!) Just let me know to check my spam folder
3. THANK YOU!!!

<sub><sup><sup>1</sup>As an anonymous observer pointed out, if DeNOTE is decrypted on the server side, it is not truly zero-knowledge. However it is open-sourced and you can run it on your own server.</sup></sub>

All code &copy; 2022 mrfakename. All rights reserved.
