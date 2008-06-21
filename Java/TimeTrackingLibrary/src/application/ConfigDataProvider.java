package application;

import application.data_beans.DBData;
import application.data_beans.MailData;

/**
 * Provides connection data
 * @author manuel
 */
public class ConfigDataProvider
{

    private static DBData m_DBData = null;
    private static int m_DBPoolSize = 0;
    private static MailData m_MailData = null;

    /**
     * 
     * @param Database connection data
     */
    public static void setDBData(DBData data)
    {
        m_DBData = data;
    }
 
    /**
     * 
     * @return Database connection data
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
    
    /**
     * 
     * @param SMTP-server connection data
     */
    public static void setMailData(MailData data)
    {
        m_MailData = data;
    }
 
    /**
     * 
     * @return SMTP-server connection data
     */
    public static MailData getMailData()
    {
        return m_MailData;
    }
    
}
