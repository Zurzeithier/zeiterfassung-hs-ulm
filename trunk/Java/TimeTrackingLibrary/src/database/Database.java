package database;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.util.logging.Level;
import java.util.logging.Logger;

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
    
    private Connection m_Connection = null;

    Database(String className, String prefix, String adress, String username, String password) throws SQLException
    {
        m_ClassName = className;
        m_AdressPrefix = prefix;
        m_Adress = adress;
        m_Username = username;
        m_Password = password;
        
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

    protected void close() throws SQLException
    {
        m_Connection.close();
        m_Connection = null;
    }
    
    public void finalizer()
    {
        if (m_Connection != null)
        {
            try
            {
                m_Connection.close();
            }
            catch (SQLException ex)
            {
                Logger.getLogger(Database.class.getName()).log(Level.SEVERE, null, ex);
            }

            m_Connection = null;
        }
    }
    
    protected Connection getConnection()
    {
        return m_Connection;
    }    
}
