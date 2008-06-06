function $( ID )
{
 if ( document.getElementById )
  return document.getElementById(ID);
 if ( document.layers )
  return document.layers[ID];
 if ( document.all )
  return document.all[ID];
 return null;
}

function E( error )
{
 $( 'errors' ).innerHTML = error;
 $( 'errors' ).style.visibility = 'visible';
 $( 'errors' ).style.display = 'block';
}