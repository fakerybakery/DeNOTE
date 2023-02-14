# DeNOTE
Welcome to DeNOTE, the private encrypted one-view-then-self-destruct note taking platform. DeNOTE is still in a beta stage, but we're planning to add more features soon.

WHAT IT DOES: DeNOTE is an encrypted, open-sourced, ~~zero-knowledge~~ no-unencrypted-data-stored<sup>1</sup> note taking platform. You input a note and password. You get a shareable link. The note is deleted when someone either successfully decrypts the note or gets the password wrong. There is zero-tolerance for getting the password wrong - the note will just be deleted forever.

Copy the `src` directory to your server. Run `INSTALL.sql` in your MySQL server and modify `init.php` to fit your needs.

## Features

- JSON API
- Self-Destruction after View
- Advanced AES-256-CBC encryption
- Many more features

## Try it out

<a href="https://www.mrfake.name/denote"><img src="https://user-images.githubusercontent.com/76186054/218612453-34865eb0-e2b5-45ca-89be-97b4da5e6859.png"></a>

## JSON API Documentation

The JSON API Documentation is available [on the project Wiki](https://github.com/fakerybakery/DeNOTE/wiki/API-Docs).

## Active Installations

<sub><sup>You can add your server by making a Pull Request.</sup></sub>


**Please note that many of the installations listed below are not owned by DeNOTE, be careful. Installations not owned by DeNOTE do NOT guarentee any type of data security or privacy!**

Do you want to see how DeNOTE works without installing it yourself? Check out the servers below!
|Server URL|Server status|Approved|Version|
|---|---|---|---|
|https://www.mrfake.name/denote/|**Active**|Approved|0.5 (latest)|

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

## Next Steps

Moved to [the project](https://github.com/users/fakerybakery/projects/1).

<sub><sup><sup>1</sup>As an anonymous observer pointed out, if DeNOTE is decrypted on the server side, it is not truly zero-knowledge. However it is open-sourced and you can run it on your own server.</sup></sub>

All code &copy; 2022 mrfakename. All rights reserved.
