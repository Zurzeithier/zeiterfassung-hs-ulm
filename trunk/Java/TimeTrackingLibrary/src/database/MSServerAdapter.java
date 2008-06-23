package database;

import beans.TPTypeBean;
import beans.TimeAccountBean;
import beans.TimeBookingBean;
import beans.TimeBookingTableEntryBean;
import beans.UserBean;
import exceptions.DBException;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.List;
import java.util.TimeZone;
import utils.LocalizeUtils;

/**
 * Adapter for Microsoft database connections which contains all querys
 * @author manuel
 */
public class MSServerAdapter extends Database implements DBAdapter
{

    /**
     * 
     * @param adress
     * @param username
     * @param password
     * @throws java.sql.SQLException
     */
    MSServerAdapter(String adress, String username, String password) throws SQLException
    {
        super("com.microsoft.sqlserver.jdbc.SQLServerDriver",
                "jdbc:sqlserver://",
                adress, username, password);

    }

    /**
     * Returns the description of a booking type from MSServer
     * @param typId
     * @return TPTypeBean
     * @throws exceptions.DBException
     */
    public TPTypeBean getType(int typId) throws DBException
    {
        try
        {

            TPTypeBean returnBean = null;
            StringBuilder query = new StringBuilder();

            query.append("SELECT TypId, Bezeichung, Symbol  FROM ZBTyp WHERE ");
            query.append("TypId='").append(typId).append("'");

            Statement stat = getConnection().createStatement();
            ResultSet res = stat.executeQuery(query.toString());


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

    /**
     * 
     * @return null
     */
    public TimeAccountBean getTimeAccount()
    {
        return null;
    }

    /**
     * Returns one user by username from MSServer
     * @param username
     * @return UserBean
     * @throws exceptions.DBException
     */
    public UserBean getUser(String username) throws DBException
    {
        try
        {
            UserBean returnBean = null;
            StringBuilder query = new StringBuilder();

            query.append("SELECT MId, Vornamen, Namen, LoginNamen, LoginPasswort FROM Mitarbeiter WHERE ");
            query.append("LoginNamen='").append(username).append("'");

            Statement stat = getConnection().createStatement();
            ResultSet res = stat.executeQuery(query.toString());


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

    /**
     * Returns one user by ID from MSServer
     * @param mid
     * @return UserBean
     * @throws exceptions.DBException
     */
    public UserBean getUser(int mid) throws DBException
    {
        try
        {
            UserBean returnBean = null;
            StringBuilder query = new StringBuilder();

            query.append("SELECT MId, Vornamen, Namen, LoginNamen, LoginPasswort FROM Mitarbeiter WHERE ");
            query.append("MId='").append(mid).append("'");

            Statement stat = getConnection().createStatement();
            ResultSet res = stat.executeQuery(query.toString());


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

    /**
     * Modifies user data on MSServer
     * @param user
     * @return Whether the operation was successful
     * @throws exceptions.DBException
     */
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

            Statement stat = getConnection().createStatement();
            int updateCount = stat.executeUpdate(query.toString());

            return (updateCount == 1);
        }
        catch (SQLException ex)
        {
            throw new DBException(ex);
        }
    }

    /**
     * Inserts one user on MSServer
     * @param user
     * @return Whether the operation was successful
     * @throws exceptions.DBException
     */
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

            Statement stat = getConnection().createStatement();
            ResultSet res = stat.executeQuery(query.toString());

            res.close();

            return true;
        }
        catch (SQLException ex)
        {
            throw new DBException(ex);
        }
    }

    /**
     * Returns a list of time bookings by ID from MSServer
     * @param mid
     * @param number
     * @return List for table entry
     * @throws exceptions.DBException
     */
    public List<TimeBookingTableEntryBean> getTimeBookings(int mid, int number) throws DBException
    {
        try
        {
            TimeBookingTableEntryBean returnBean = null;
            List<TimeBookingTableEntryBean> returnList = new ArrayList();
            StringBuilder query = new StringBuilder();

            query.append("SELECT");
            if (number > -1)
            {
                query.append(" TOP ").append(number);   // set number of rows that should be returned
            }
            query.append(" Namen, Vornamen, Start_Datum, End_Datum");
            query.append(" FROM View_GetNextBuchung gb");
            query.append(" INNER JOIN Mitarbeiter mi");
            query.append(" ON gb.mid = mi.mid");
            query.append(" WHERE mi.mid='").append(mid).append("'");
            query.append(" ORDER BY Start_Datum DESC");

            Statement stat = getConnection().createStatement();
            ResultSet res = stat.executeQuery(query.toString());


            while (res.next())
            {
                returnBean = new TimeBookingTableEntryBean();

                returnBean.setFirstname(res.getString("Vornamen"));
                returnBean.setLastname(res.getString("Namen"));

                // read utc time from db and localize this time              
                returnBean.setComeBooking(LocalizeUtils.localizeDate(res.getTimestamp("Start_Datum")));
                returnBean.setGoBooking(LocalizeUtils.localizeDate(res.getTimestamp("End_Datum")));

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

    /**
     * Adds one time booking to the MSServer database
     * @param bean
     * @return Whether the operation was successful
     * @throws exceptions.DBException
     */
    public boolean addTimeBooking(TimeBookingBean bean) throws DBException
    {
        try
        {
            StringBuilder query = new StringBuilder();

            query.append("INSERT INTO ZeitBuchung");
            query.append(" (TypId, Datum, Mid, KstId, KoaId)");
            query.append(" VALUES (");
            query.append(bean.getTypId()).append(", ");

            // format localized time to the right string format and to utc time
            SimpleDateFormat dateFormatter = new SimpleDateFormat("yyyyMMdd HH:mm:ss.SSS");
            dateFormatter.setTimeZone(TimeZone.getTimeZone("GMT"));
            query.append("'").append(dateFormatter.format(bean.getDate())).append("', ");

            query.append(bean.getMid()).append(", ");
            query.append(bean.getKstId()).append(", ");
            query.append(bean.getKoaId());
            query.append(")");

            Statement stat = getConnection().createStatement();
            int insertCount = stat.executeUpdate(query.toString());

            return (insertCount == 1);
        }
        catch (SQLException ex)
        {
            throw new DBException(ex);
        }
    }

    /**
     * Returns the last time booking from MSServer
     * @param mid
     * @return TimeBookingBean
     * @throws exceptions.DBException
     */
    public TimeBookingBean getLastBooking(int mid) throws DBException
    {
        try
        {
            TimeBookingBean returnBean = null;
            StringBuilder query = new StringBuilder();

            query.append("SELECT TOP 1 Bid, TypId, Datum, Mid, KstId, KoaId");
            query.append(" FROM ZeitBuchung"); 
            query.append(" WHERE Mid='").append(mid).append("'");
            query.append(" ORDER BY Datum DESC");

            Statement stat = getConnection().createStatement();
            ResultSet res = stat.executeQuery(query.toString());


            if (res.next())
            {
                returnBean = new TimeBookingBean();

                returnBean.setBid(res.getInt("Bid"));
                returnBean.setMid(res.getInt("MId"));
                returnBean.setDate(LocalizeUtils.localizeDate(res.getTimestamp("Datum")));
                returnBean.setTypId(res.getInt("TypId"));
                returnBean.setKoaId(res.getInt("KoaId"));
                returnBean.setKstId(res.getInt("KstId"));
            }

            res.close();

            return returnBean;


        }
        catch (SQLException ex)
        {
            throw new DBException(ex);
        }
    }

}
