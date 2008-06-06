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
    
    protected String m_ClassName = null;
    protected String m_AdressPrefix = null;
    
    protected Connection m_Connection;

    public Database(String adress, String username, String password)
    {
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
}
