/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package database;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

/**
 *
 * @author manuel
 */
public class MSServer 
{
    private String m_Adress;
    private String m_Username;
    private String m_Password;
    protected Connection m_Connection;

    public MSServer(String adress, String username, String password)
    {
        m_Adress = adress;
        m_Username = username;
        m_Password = password;

    }

    protected void connect() throws SQLException
    {
        try
        {
            Class.forName("net.sourceforge.jtds.jdbc.Driver").newInstance();
            m_Connection = DriverManager.getConnection("jdbc:jtds:sqlserver://" + m_Adress,  m_Username, m_Password);
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
