# DeNOTE
Welcome to DeNOTE, the private encrypted one-view-then-self-destruct note taking platform. DeNOTE is still in a beta stage, but we're planning to add more features soon.

WHAT IT DOES: DeNOTE is an encrypted, open-sourced, ~~zero-knowledge~~ no-unencrypted-data-stored<sup>1</sup> note taking platform. You input a note and password. You get a shareable link. The note is deleted when someone either successfully decrypts the note or gets the password wrong. There is zero-tolerance for getting the password wrong - the note will just be deleted forever.

## Installation

For installation instructions, please review [the wiki](https://github.com/fakerybakery/DeNOTE/wiki/Installation).

## Features

- JSON API
- Self-Destruction after View
- Advanced AES-256-CBC encryption
- Many more features

## Try it out

<a href="https://www.mrfake.name/denote"><img src="https://user-images.githubusercontent.com/76186054/218871729-39fba215-897a-489e-a92a-31edbd7169a6.png"></a>

## JSON API Documentation

The JSON API Documentation is available [on the project Wiki](https://github.com/fakerybakery/DeNOTE/wiki/API-Docs).

## Active Installations

<sub><sup>You can add your server by making a Pull Request.</sup></sub>


**Please note that many of the installations listed below are not owned by DeNOTE, be careful. Installations not owned by DeNOTE do NOT guarentee any type of data security or privacy!**

Do you want to see how DeNOTE works without installing it yourself? Check out the servers below!
|Server URL|Server status|Approved|Version|
|---|---|---|---|
|https://www.mrfake.name/denote/|**Active**|Approved|0.5 (latest)|

## How to report a security vulnerability

Please, email me! My email is me [ at ] mrfake [ dot ] name!

## Need Help?

Please file a GitHub issue or email me.

## Next Steps

Moved to [the project](https://github.com/users/fakerybakery/projects/1).

<sub><sup><sup>1</sup>As an anonymous observer pointed out, if DeNOTE is decrypted on the server side, it is not truly zero-knowledge. However it is open-sourced and you can run it on your own server.</sup></sub>

All code &copy; 2022 mrfakename. All rights reserved.
