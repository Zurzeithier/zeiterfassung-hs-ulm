package database;

/**
 *
 * @author manuel
 */
public class ConnectionDataProvider
{

    private static String m_Adress = null;
    private static String m_Username = null;
    private static String m_Password = null;
    private static boolean m_Initialized = false;

    public static String getAdress()
    {
        if (!m_Initialized)
        {
            initialize();
        }

        return m_Adress;
    }

    public static String getPassword()
    {
        if (!m_Initialized)
        {
            initialize();
        }
        
        return m_Password;
    }

    public static String getUsername()
    {
        if (!m_Initialized)
        {
            initialize();
        }
        
        return m_Username;
    }

    private static void initialize()
    {
        m_Adress = "www.illertech.net;databaseName=Zeiterfassung";
        m_Username = "sa";
        m_Password = "odysee2001";
    }

}
