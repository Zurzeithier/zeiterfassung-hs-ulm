package application;

import time_tracking.UserBean;

/**
 *
 * @author manuel, steffen
 */
public class Application
{
    private time_tracking.UserBean user = null;
    static private Application m_Instance = null;
    
    private Application()
    {
    }

    /**
     * 
     * @return user
     */
    public UserBean getUser()
    {
        return user;
    }

    /**
     * 
     * @param user
     */
    public void setUser(UserBean user)
    {
        this.user = user;
    }

    /**
     * 
     * @return m_Instance
     */
    static public Application getInstance()
    {
        if (m_Instance == null)
        {
            m_Instance = new Application();
        }

        return m_Instance;
    }

}
