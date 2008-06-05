/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package database;

import beans.TPTypeBean;
import errors.DBError;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

/**
 *
 * @author manuel, steffen
 */
public class MSServerTPTypeProxy extends MSServer implements TPTypeProxy
{

    public MSServerTPTypeProxy(String adress, String username, String password)
    {
        super(adress, username, password);
    }

    public TPTypeBean getType(int typId)
    {
        try
        {
            connect();

            TPTypeBean returnBean = null;
            StringBuilder query = new StringBuilder();

            query.append("SELECT *  FROM ZBTyp WHERE ");
            query.append("TypId='");
            query.append(typId);
            query.append("'");

            Statement sat = m_Connection.createStatement();
            ResultSet res = sat.executeQuery(query.toString());


            if (res.next())
            {
                returnBean = new TPTypeBean();

                returnBean.setTypId(res.getInt("TypId"));
                returnBean.setName(res.getString("Bezeichnung"));
                returnBean.setSymbol(res.getString("Symbol"));
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

}
