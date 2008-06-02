/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package database;

import beans.TPTypeBean;

/**
 *
 * @author manuel
 */
public class MSServerTPTypeProxy extends MSServer implements TPTypeProxy
{

    public MSServerTPTypeProxy(String adress, String username, String password)
    {
        super(adress, username, password);
    }

    public TPTypeBean getType()
    {
        return null;
    }

}
