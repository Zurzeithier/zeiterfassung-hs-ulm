/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package application;

import time_tracking.UserBean;

/**
 *
 * @author manuel
 */
public class Application
{
    private time_tracking.UserBean user = null;
    
    private Application()
    {
    }

    public UserBean getUser()
    {
        return user;
    }

    public void setUser(UserBean user)
    {
        this.user = user;
    }
    
    
    
    

    static private Application m_Instance = null;

    static public Application getInstance()
    {
        if (m_Instance == null)
        {
            m_Instance = new Application();
        }

        return m_Instance;
    }

}
