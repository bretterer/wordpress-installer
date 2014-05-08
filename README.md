WordPress Command Line Interface
================================
___

Install
-------

This is the codebase used to generate the wordpress.phar file.

You can download the wordpress.phar file with the following.

```bash
wget https://raw.github.com/bretterer/wordpress-installer/master/wordpress.phar
sudo cp wordpress.phar /usr/bin/wordpress
sudo chmod +x /usr/bin/wordpress
```
___

Usage
-----
from the terminal, go into the directory you want to build your site in.  

```bash
cd ~/Sites
```

Build a new wordpress site. 
```
wordpress new mysite.dev
```
This will create a new wordpress installation at `~/Sites/mysite.dev`

Once the installation is done, navigate to the development site and run the install like normal.  If you would like to reset the keys to new keys, run the following command from the `mysite.dev` folder

```bash
wordpress keys
```

___


Thanks to
---------

* Taylor Otwell, creator of Laravel, for the awesome illuminate library.
* Symfony2 Team for the great console component.
