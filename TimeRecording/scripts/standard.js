/**
 * return crossbrowser element by id
 */
function $( ID )
{
 if ( document.getElementById )
 {
  return document.getElementById(ID);
 }
 if ( document.layers )
 {
  return document.layers[ID];
 }
 if ( document.all )
 {
  return document.all[ID];
 }
 return null;
}
