package database;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 * Superclass for database connections
 * @author manuel
 */
public class Database
{
    private final String m_Adress;
    private final String m_Username;
    private final String m_Password;
    
    private final String m_ClassName;
    private final String m_AdressPrefix;
    
    /**
     * Database connection object
     */
    private Connection m_Connection = null;

    /**
     * Opens new database connection
     * @param className JDBC driver class
     * @param prefix JDBC driver depending prefix
     * @param adress
     * @param username
     * @param password
     * @throws java.sql.SQLException
     */
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

    /**
     * Closes the database connection
     * @throws java.sql.SQLException
     */
    protected void close() throws SQLException
    {
        m_Connection.close();
        m_Connection = null;
    }
    
    /**
     * Closes the database connection if necessary
     */
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
    
    /**
     * 
     * @return Database connection object
     */
    protected Connection getConnection()
    {
        return m_Connection;
    }    
}
