package handlers;

import beans.UserBean;
import database.AdapterPool;
import database.DBAdapter;
import exceptions.DBException;
import pool.ObjectPoolException;
import utils.SecurityUtils;

/**
 *
 * @author manuel
 */
public class UserHandler
{
    /**
     * 
     * @param username
     * @param password
     * @return
     * @throws DBException 
     */
    public static UserBean LoginUser(String username, String password) throws DBException
    {
        try
        {
            DBAdapter adapter = AdapterPool.getDBAdapter();
            
          /*  UserBean temp = adapter.getUser("manv");
            temp.setPassword(SecurityUtils.makeMD5Checksum("geheim"));
            adapter.changeUser(temp);
            */
            UserBean returnValue = null;

            UserBean dbBean = adapter.getUser(username);

            if (dbBean != null && dbBean.getPassword().equals(SecurityUtils.makeMD5Checksum(password)))
            {
                returnValue = dbBean;
            }

            AdapterPool.releaseDBAdapter(adapter);
            return returnValue;
        }
        catch (ObjectPoolException ex)
        {
           throw new DBException(ex);
        }
    }

    /**
     * Ändert das Passwort eines Users falls der übergebene Username
     * und das Passwort stimmen.
     * 
     * @param mid Mid des zu ändernden Users
     * @param password Altes Passwort das geändert werden soll
     * @param newPassword Neues Passwort
     * @return
     * @throws DBException 
     */
    public static boolean ChangeUserPWD(int mid, String password, String newPassword) throws DBException
    {
        try
        {
            DBAdapter adapter = AdapterPool.getDBAdapter();
            boolean returnValue = false;

            UserBean dbBean = adapter.getUser(mid);

            if (dbBean != null && dbBean.getPassword().equals(SecurityUtils.makeMD5Checksum(password)))
            {
                UserBean bean = new UserBean();
                bean.setMid(dbBean.getMid());
                bean.setPassword(SecurityUtils.makeMD5Checksum(newPassword));

                returnValue = adapter.changeUser(bean);
            }

            AdapterPool.releaseDBAdapter(adapter);
            return returnValue;
        }
        catch (ObjectPoolException ex)
        {
            throw new DBException(ex);
        }
    }
}
