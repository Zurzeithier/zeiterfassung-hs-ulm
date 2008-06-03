package application;

import application.data_beans.DBData;

/**
 *
 * @author manuel
 */
public class ConfigDataProvider
{

    private static DBData m_DBData = null;

    public static void setDBData(DBData data)
    {
        m_DBData = data;
    }

    public static DBData getDBData()
    {
        return m_DBData;
    }

}
