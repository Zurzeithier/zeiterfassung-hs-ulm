package database;

import beans.UserBean;
import errors.DBError;
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

    public UserBean getUser(String username) throws DBError
    {
        try
        {
            connect();

            UserBean returnBean = null;
            StringBuilder query = new StringBuilder();

            query.append("SELECT *  FROM Mitarbeiter WHERE ");
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
            throw new DBError(ex);
        }
    }

    public UserBean getUser(int mid)
    {
        try
        {
            connect();

            UserBean returnBean = null;
            StringBuilder query = new StringBuilder();

            query.append("SELECT *  FROM Mitarbeiter WHERE ");
            query.append("MId='");
            query.append(mid);
            query.append("'");

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
            throw new DBError(ex);
        }
    }

    public boolean changeUser(UserBean user)
    {
        return false;
    }

    // nicht getestet ob der MSServer die MID automatisch erhöht
    public boolean insertUser(UserBean user)
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
            throw new DBError(ex);
        }
    }

}
