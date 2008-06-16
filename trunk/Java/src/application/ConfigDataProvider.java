package application;

import application.data_beans.DBData;

/**
 *
 * @author manuel
 */
public class ConfigDataProvider
{

    private static DBData m_DBData = null;
    private static int m_DBPoolSize = 0;

    public static void setDBData(DBData data)
    {
        m_DBData = data;
    }

    public static DBData getDBData()
    {
        return m_DBData;
    }

    public static int getDBPoolSize()
    {
        return m_DBPoolSize;
    }

    public static void setDBPoolSize(int DBPoolSize)
    {
        ConfigDataProvider.m_DBPoolSize = DBPoolSize;
    }

    
}
