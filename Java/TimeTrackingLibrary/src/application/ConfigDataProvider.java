package application;

import application.data_beans.DBData;

/**
 * Provides connection data
 * @author manuel
 */
public class ConfigDataProvider
{

    private static DBData m_DBData = null;
    private static int m_DBPoolSize = 0;

    /**
     * 
     * @param data Connection data
     */
    public static void setDBData(DBData data)
    {
        m_DBData = data;
    }
 
    /**
     * 
     * @return Connection data
     */
    public static DBData getDBData()
    {
        return m_DBData;
    }
    
    /**
    * 
    * @param DBPoolSize Maximum database connections
    */
    public static void setDBPoolSize(int DBPoolSize)
    {
        ConfigDataProvider.m_DBPoolSize = DBPoolSize;
    }
    
     /**
     * 
     * @return Maximum database connections
     */
    public static int getDBPoolSize()
    {
        return m_DBPoolSize;
    }
    
}
