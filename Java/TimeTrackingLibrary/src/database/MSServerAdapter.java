package database;

import beans.TPTypeBean;
import beans.TimeAccountBean;
import beans.TimePostingBean;
import beans.UserBean;
import exceptions.DBException;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;

/**
 *
 * @author manuel
 */
public class MSServerAdapter extends Database implements DBAdapter
{

    MSServerAdapter(String adress, String username, String password) throws SQLException
    {
        super("com.microsoft.sqlserver.jdbc.SQLServerDriver",
                "jdbc:sqlserver://",
                adress, username, password);

    }

    public TPTypeBean getType(int typId) throws DBException
    {
        try
        {

            TPTypeBean returnBean = null;
            StringBuilder query = new StringBuilder();

            query.append("SELECT TypId, Bezeichung, Symbol  FROM ZBTyp WHERE ");
            query.append("TypId='").append(typId).append("'");

            Statement sat = getConnection().createStatement();
            ResultSet res = sat.executeQuery(query.toString());


            if (res.next())
            {
                returnBean = new TPTypeBean();

                returnBean.setTypId(res.getInt("TypId"));
                returnBean.setName(res.getString("Bezeichnung"));
                returnBean.setSymbol(res.getString("Symbol"));
            }

            res.close();

            return returnBean;
        }
        catch (SQLException ex)
        {
            throw new DBException(ex);
        }
    }

    public TimeAccountBean getTimeAccount()
    {
        return null;
    }

    public List<TimePostingBean> getTimePosting(int mid) throws DBException
    {
        try
        {
            TimePostingBean returnBean = null;
            List returnList = new ArrayList();
            StringBuilder query = new StringBuilder();

            query.append("SELECT MId, BId, Datum, KoaId, KstId, TypId FROM ZeitBuchung WHERE ");
            query.append("MId='").append(mid).append("'");

            Statement sat = getConnection().createStatement();
            ResultSet res = sat.executeQuery(query.toString());


            while (res.next())
            {
                returnBean = new TimePostingBean();

                returnBean.setMid(res.getInt("MId"));
                returnBean.setBid(res.getInt("BId"));
                returnBean.setDate(res.getDate("Datum"));
                returnBean.setKoaId(res.getInt("KoaId"));
                returnBean.setKstId(res.getInt("KstId"));
                returnBean.setTypId(res.getInt("TypId"));

                returnList.add(returnBean);
            }

            res.close();

            return returnList;
        }
        catch (SQLException ex)
        {
            throw new DBException(ex);
        }
    }

    public UserBean getUser(String username) throws DBException
    {
        try
        {
            UserBean returnBean = null;
            StringBuilder query = new StringBuilder();

            query.append("SELECT MId, Vornamen, Namen, LoginNamen, LoginPasswort FROM Mitarbeiter WHERE ");
            query.append("LoginNamen='").append(username).append("'");

            // System.out.println(query.toString());
            Statement sat = getConnection().createStatement();
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
            UserBean returnBean = null;
            StringBuilder query = new StringBuilder();

            query.append("SELECT MId, Vornamen, Namen, LoginNamen, LoginPasswort FROM Mitarbeiter WHERE ");
            query.append("MId='").append(mid).append("'");

            Statement sat = getConnection().createStatement();
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
            StringBuilder query = new StringBuilder();
            
            query.append("UPDATE Mitarbeiter");
            String between = " SET ";
            
            if (user.getFirstname() != null)
            {
                query.append(between);
                query.append("Vornamen='").append(user.getFirstname()).append("'");
                between = ", ";
            }
            if (user.getName() != null)
            {
                query.append(between);
                query.append("Namen='").append(user.getName()).append("'");
                between = ", ";
            }
            if (user.getUsername() != null)
            {
                query.append(between);
                query.append("LoginNamen='").append(user.getUsername()).append("'");
                between = ", ";
            }
            if (user.getPassword() != null)
            {
                query.append(between);
                query.append("LoginPasswort='").append(user.getPassword()).append("'");
                between = ", ";
            }

            query.append(" WHERE mid='").append(user.getMid()).append("'");
            
            Statement sat = getConnection().createStatement();
            boolean sucessfull = sat.execute(query.toString());

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
            StringBuilder query = new StringBuilder();

            query.append("INSERT INTO Mitarbeiter ");
            query.append("(Namen, Vornamen, LoginNamen, LoginPasswort) VALUES (");
            query.append(user.getName()).append(", ");
            query.append(user.getFirstname()).append(", ");
            query.append(user.getUsername()).append(", ");
            query.append(user.getPassword()).append(")");

            Statement sat = getConnection().createStatement();
            ResultSet res = sat.executeQuery(query.toString());

            res.close();

            return true;
        }
        catch (SQLException ex)
        {
            throw new DBException(ex);
        }
    }

}
