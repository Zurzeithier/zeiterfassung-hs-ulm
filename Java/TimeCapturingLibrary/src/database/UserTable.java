package database;

import beans.UserBean;
import java.sql.*;

/**
 *
 * @author manuel
 */
public class UserTable
{

    private String m_Adress;
    private String m_Username;
    private String m_Password;
    private Connection m_Connection;
    
    
    public UserTable(String adress, String username, String password)
    {
        m_Adress = adress;      // wwww.illertech.net/Zeiterfassung
        m_Username = username;  // sa
        m_Password = password;  // odysee2001
   
    }
    
    private void connect() throws SQLException
    {        
        try
        {
            Class.forName("net.sourceforge.jtds.jdbc.Driver").newInstance();
            m_Connection = DriverManager.getConnection("jdbc:jtds:sqlserver://" + m_Adress, m_Username, m_Password);
        }
        catch (Exception e)
        {
            throw new SQLException(e);
        }
    }
    
    private void disconnect() throws SQLException
    {
        m_Connection.close();
    }


    public UserBean getUser(String username, String password) throws SQLException
    {
        connect();
        
        
        disconnect();
        
        return null;
    }

    public UserBean getUser(int mid)
    {

        return null;
    }
    
    public void changeUser(UserBean user)
    {
        
    }
}
