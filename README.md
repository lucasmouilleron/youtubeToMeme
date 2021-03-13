youtubeToMeme
=============

Youtube to animated meme generator.
![Screenshot](http://grabs.lucasmouilleron.com/grab%202021-03-13%20at%2009.19.39.png)

Features
--------
- Downloads a Youtube video, converts it to an animated gif and adds a custom meme text on top of it
- Colors, auto linebreak
- Based on nodejs youtube-dl and gifify
- PHP image magick gif frame annotation

Install
-------
- Install NodeJS : http://nodejs.org/download
- ffmpeg : `brew install ffmpeg`
- libtool : `brew install libtool`
- imagemagick : `brew install imagemagick --build-from-source`
- giflossy :
    - `brew install automake`
    - `git clone git@github.com:pornel/giflossy.git && cd giflossy && autoreconf -i && ./configure && make && make install`
- imagick php :
    - `sudo pecl install imagick`
    - `vi /etc/php.ini` and add `extension=imagick.so`
- `cd libs/youtubeToGif && npm install`

Run
---
- `php cmd.php A7TaY8HWYd8 71 5 "PRETTY GOOD JUNIOR" 500 BOTTOM`
- `php cmd.php 6Hn8qnsucwo 30 5 "PRETTY ... PRETTY ... PRETTY ... PRETTY ... PRETTY GOOD" 500 TOP`
- `php cmd.php puo1Enh9h5k 49 2 "ROMANO LE RELOU" 500 BOTTOM`

TODO
----
- minimal UI
- Linux install
- POSITION_BOTTOM flickers