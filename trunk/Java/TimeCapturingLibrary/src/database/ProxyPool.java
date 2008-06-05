package database;

import application.ConfigDataProvider;

/**
 *
 * @author manuel, steffen
 */
public class ProxyPool
{

    private static UserProxy m_UserProxy = null;
    private static TimeAccountProxy m_TimeAccountProxy = null;
    private static TimePostingProxy m_TimePostingProxy = null;
    private static TPTypeProxy m_TPTypeProxy = null;

    public static UserProxy getUserProxy()
    {
        if (m_UserProxy == null)
        {
            m_UserProxy = new MSServerUserProxy(
                    ConfigDataProvider.getDBData().adress,
                    ConfigDataProvider.getDBData().user,
                    ConfigDataProvider.getDBData().password);
        }

        return m_UserProxy;
    }

    public static TimeAccountProxy getTimeAccountProxy()
    {
        if (m_TimeAccountProxy == null)
        {
            m_TimeAccountProxy = new MSServerTimeAccountProxy(
                    ConfigDataProvider.getDBData().adress,
                    ConfigDataProvider.getDBData().user,
                    ConfigDataProvider.getDBData().password);
        }

        return m_TimeAccountProxy;
    }

    public static TimePostingProxy getPostingProxy()
    {
        if (m_TimePostingProxy == null)
        {
            m_TimePostingProxy = new MSServerTimePostingProxy(
                    ConfigDataProvider.getDBData().adress,
                    ConfigDataProvider.getDBData().user,
                    ConfigDataProvider.getDBData().password);
        }

        return m_TimePostingProxy;
    }

    public static TPTypeProxy getTPTypeProxy()
    {        
        if (m_TPTypeProxy == null)
        {
            m_TPTypeProxy = new MSServerTPTypeProxy(
                    ConfigDataProvider.getDBData().adress,
                    ConfigDataProvider.getDBData().user,
                    ConfigDataProvider.getDBData().password);
        }

        return m_TPTypeProxy;        
    }

}

