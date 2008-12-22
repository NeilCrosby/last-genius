# Last Genius

Code written for Last.fm's 14th December 2008 Hack Day in London.  It's fairly simple - start with any given song and Last Genius will create a playlist of songs that go with that initial song, and will start playing them.  Only songs which are able to played all the way through by last.fm will be added to the playlist.

I wrote a [blog post about Last Genius][1], and Last.fm mentioned Last Genius in their [round-up of the Hack Day][2].

## Bookmarklet

To start using Last Genius, drag the bookmarklet below into your browser's toolbar.
    
Drag this: [Last Genius][3]

Then, any time you're on a Last.fm song page that you'd like to create a Genius Playlist for, just click on the bookmarklet and magic will happen.

[1]: http://thecodetrain.co.uk/2008/12/last-genius-a-lastfm-hack-day-project/
[2]: http://blog.last.fm/2008/12/22/hack-day-2008
[3]: javascript:void(window.open('http://projects.thecodetrain.co.uk/last-genius/playlist.php?url='+location.href,'mywin','left=20,top=20,width=700,height=300,toolbar=0,resizable=1'));
