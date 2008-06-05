package database;

import beans.UserBean;
import exceptions.DBException;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

/**
 *
 * @author manuel, steffen
 */
public class MSServerUserProxy extends MSServer implements UserProxy
{

    public MSServerUserProxy(String adress, String username, String password)
    {
        super(adress, username, password);
    }

    public UserBean getUser(String username) throws DBException
    {
        try
        {
            connect();

            UserBean returnBean = null;
            StringBuilder query = new StringBuilder();

            query.append("SELECT MId, Vornamen, Namen, LoginNamen, LoginPasswort FROM Mitarbeiter WHERE ");
            query.append("LoginNamen='");
            query.append(username);
            query.append("'");

            // System.out.println(query.toString());
            Statement sat = m_Connection.createStatement();
            ResultSet res = sat.executeQuery(query.toString());


            if (res.next())
            {
                returnBean = new UserBean();

                returnBean.setMid(res.getInt("Mid"));
                returnBean.setFirstname(res.getString("Vornamen"));
                returnBean.setName(res.getString("Namen"));
                returnBean.setUsername(res.getString("LoginNamen"));
                returnBean.setPassword(res.getString("LoginPasswort"));
            }

            res.close();
            disconnect();

            return returnBean;
        }
        catch (SQLException ex)
        {
            throw new DBException(ex);
        }
    }

    public UserBean getUser(int mid) throws DBException
    {
        try
        {
            connect();

            UserBean returnBean = null;
            StringBuilder query = new StringBuilder();

            query.append("SELECT MId, Vornamen, Namen, LoginNamen, LoginPasswort FROM Mitarbeiter WHERE ");
            query.append("MId='");
            query.append(mid);
            query.append("'");

            Statement sat = m_Connection.createStatement();
            ResultSet res = sat.executeQuery(query.toString());


            if (res.next())
            {
                returnBean = new UserBean();

                returnBean.setMid(res.getInt("MId"));
                returnBean.setFirstname(res.getString("Vornamen"));
                returnBean.setName(res.getString("Namen"));
                returnBean.setUsername(res.getString("LoginNamen"));
                returnBean.setPassword(res.getString("LoginPasswort"));
            }

            res.close();
            disconnect();

            return returnBean;
        }
        catch (SQLException ex)
        {
            throw new DBException(ex);
        }
    }

    public boolean changeUser(UserBean user) throws DBException
    {
        try
        {
            connect();

            StringBuilder query = new StringBuilder();

            query.append("UPDATE Mitarbeiter SET ");
            
            if (user.getFirstname() != null)
            {
                query.append("Vornamen='").append(user.getFirstname()).append("'");
            }
            if (user.getName() != null)
            {
                query.append("Namen='").append(user.getName()).append("'");
            }
            if (user.getUsername() != null)
            {
                query.append("LoginNamen='").append(user.getUsername()).append("'");
            }
            if (user.getPassword() != null)
            {
                query.append("LoginPasswort='").append(user.getPassword()).append("'");
            }
           
            query.append(" WHERE mid='").append(user.getMid()).append("'");

            // System.out.println(query.toString());
            Statement sat = m_Connection.createStatement();
            boolean sucessfull = sat.execute(query.toString());

            disconnect();
            
            return sucessfull;
        }
        catch (SQLException ex)
        {
            throw new DBException(ex);
        }
    }

    // nicht getestet ob der MSServer die MID automatisch erhöht
    public boolean insertUser(UserBean user) throws DBException
    {
        try
        {
            connect();
            
            StringBuilder query = new StringBuilder();

            query.append("INSERT INTO Mitarbeiter ");
            query.append("(Namen, Vornamen, LoginNamen, LoginPasswort) VALUES (");
            query.append(user.getName());
            query.append(", ");
            query.append(user.getFirstname());
            query.append(", ");
            query.append(user.getUsername());
            query.append(", ");
            query.append(user.getPassword());
            query.append(")");

            Statement sat = m_Connection.createStatement();
            ResultSet res = sat.executeQuery(query.toString());

            res.close();
            disconnect();

            return true;
        }
        catch (SQLException ex)
        {
            throw new DBException(ex);
        }
    }

}
