package database;

import beans.TimePostingBean;
import exceptions.DBException;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;

/**
 *
 * @author manuel, steffen
 */
public class MSServerTimePostingProxy extends MSServer implements TimePostingProxy
{

    public MSServerTimePostingProxy(String adress, String username, String password)
    {
        super(adress, username, password);
    }

    public List getTimePosting(int mid) throws DBException
    {
        try
        {
            connect();

            TimePostingBean returnBean = null;
            List returnList = new ArrayList();
            StringBuilder query = new StringBuilder();

            query.append("SELECT *  FROM ZeitBuchung WHERE ");
            query.append("MId='");
            query.append(mid);
            query.append("'");

            Statement sat = m_Connection.createStatement();
            ResultSet res = sat.executeQuery(query.toString());


            while (res.next())
            {
                returnBean = new TimePostingBean();

                returnBean.setMid(res.getInt("Mid"));
                returnBean.setBid(res.getInt("Bid"));
                returnBean.setDate(res.getDate("Datum"));
                returnBean.setKoaId(res.getInt("KoaId"));
                returnBean.setKstId(res.getInt("KstId"));
                returnBean.setTypId(res.getInt("TypId"));
                
                returnList.add(returnBean);
            }

            res.close();
            disconnect();

            return returnList;
        }
        catch (SQLException ex)
        {
            throw new DBException(ex);
        }
    }
}
