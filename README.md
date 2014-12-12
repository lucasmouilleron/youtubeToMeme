youtubeToMeme
=============

![Demo](assets/demo.gif)

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
- `php meme.php`

TODO
----
- minimal UI
- Better run
- Linux install
- POSITION_BOTTOM flickers