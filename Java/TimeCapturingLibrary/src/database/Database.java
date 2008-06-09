package database;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

/**
 *
 * @author manuel
 */
public class Database
{
    private final String m_Adress;
    private final String m_Username;
    private final String m_Password;
    
    private final String m_ClassName;
    private final String m_AdressPrefix;
    
    private Connection m_Connection;

    public Database(String className, String prefix, String adress, String username, String password)
    {
        m_ClassName = className;
        m_AdressPrefix = prefix;
        m_Adress = adress;
        m_Username = username;
        m_Password = password;

    }

    protected void connect() throws SQLException
    {
        try
        {
            Class.forName(m_ClassName).newInstance();
            m_Connection = DriverManager.getConnection(m_AdressPrefix + m_Adress,  m_Username, m_Password);
        }
        catch (Exception e)
        {
            throw new SQLException(e);
        }
    }

    protected void disconnect() throws SQLException
    {
        m_Connection.close();
    }
    
    public Connection getConnection()
    {
        return m_Connection;
    }    
}
