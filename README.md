# personal-log
A small personal logging software that I built in about 3 hours for no particular reason.

It uses my [Small Template Framework](https://github.com/akosnikhazy/Small-Template-Framework) as a base for this and still this is a skeleton of a project but it can save and search by tags and search by text with the search.php?tag= and search.php?text= parameters.

The only formating it knows is line break. One linebreak will create a paragraph wiht `<p></p>` tags. It clears any empty p tags before saving.

# Install
The database is in the personallog.sql file. Just import it and you have the database.

The default password is `admin`. You can change this in the most annoying way: in the login.php file you will find a commented out `die()` with a hash and salt.

```
// die(hash('sha256','admin' . '4b21bea153fb8dfe3fc53eb9b94cd463dc5cfcd8718bdc423a4f1afc6eacaaf3'));
```
Just write your password instead of "admin" and a random salt. Run the script. Copy the salted password (the result of the hash function) and the salt (you wrote in the code) into the pw file, separated by x (warning: do not put x in the salt ;D).

If you really want to use this thing, you should come up with your own salting algorithm instead of the example pw.salt you see in the code. Then you should change the following line too:
```
if($pwxsalt[0] == hash('sha256',$_POST['openthelogplease'] . $pwxsalt[1]))
```
Also you might want to .htaccess deny the pw file, to be safe. Not that this should run on the WWW. But for a home network, as something running privately: its fine.

# How to use
You post the things you want to log. You can tag the posts, tags,are,separated,by,commas. It will lowercase them and trim spaces. Tags are automatically indexed and counted. If you click on a tag, it will show you all the posts with that tag. You can also read a post by clicking on its date. You can use any html, you can break it like that.

# Why
Because I was bored. Really. You can build on it. Do whatever you want with it. It really is the result of 3 hours. You need a lot more than that to make it really good. And safe. And useful.
