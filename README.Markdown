# Last Genius

Code written for Last.fm's 14th December 2008 Hack Day in London.  It's fairly simple - start with any given song and Last Genius will create a playlist of songs that go with that initial song, and will start playing them.  Only songs which are able to played all the way through by last.fm will be added to the playlist.

## Bookmarklet

    javascript:void(window.open('http://projects.thecodetrain.co.uk/last-genius/playlist.php?url='+location.href,'mywin','left=20,top=20,width=700,height=300,toolbar=0,resizable=1'));