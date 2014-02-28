Laravel - OneTimeNote
=========

A small Laravel 4.1.x REST application that allows you to pass a JSON formatted note and securely store the note in a database. The application will provide a one time use URL to decrypt and show the note. Once the url is requested the note faces it's impending doom.

Created By
----
Andrew Del Prete ([@pathsofdesign])

Version
----

1.0

Dependencies
-----------
* PHP >= 5.3.7
* MySQL
* MCrypt PHP Extension
* SMTP, PHP Mail, or Sendmail.
* SSL
* Composer

How is it secure?
--------------
When a user creates a note, two random 16 character strings are created. One of these strings is stored in the DB to find the entry later, and a combination of the two strings are used to encrypt the note and store it in the DB. The user is provided with a URL that has both strings in it. Since each note is encrypted with completely different keys, only the person who attains the URL can decrypt the message.

Installation
--------------

1. Clone the repo git@github.com:Pathsofdesign/OneTimeNote.git
2. Create a new MySQL database 
3. Run 'composer install' from the CLI
4. Edit app/config/app.php and add database credentials
5. Adjust environment settings in app/bootstrap/start.php
6. Configure mail settings in app/config/mail.php
7. Run Laravel migration 'php artisan migrate' from the CLI

**The person creating the note must take care how they share the URL.*

**The server MUST be running a valid SSL.*

How to create a note
--------------

HTTP POST a note as valid JSON syntax with the HTTP Content-Type set as 'application/json'.

```json
{
    "secure_note": "Yo Ho, Let's go!",
	"email": "email@address.com",
}
```

**Returns**
```json
{
    "message":"Note Created",
    "note_url":"https:\/\/localhost\/note\/GMwQTOcANV5kDpXc\/nD8R3M05pE2Cynx4"
}
```
**The 'email' property isn't necessary but can be used to send the note creator an e-mail once the note is requested and destroyed*

How to retrieve a note
--------------
HTTP GET the URL provided https://localhost/note/GMwQTOcANV5kDpXc/nD8R3M05pE2Cynx4
```json
{
    "message":"Note Destroyed",
    "secure_note": "Yo Ho, Let's go!",
}
```

Demo
----
Coming Soon

License
----
MIT

[@pathsofdesign]:http://www.twitter.com/pathsofdesign