package handlers;

import beans.UserBean;
import database.AdapterPool;
import database.DBAdapter;
import exceptions.DBException;
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
        DBAdapter adapter = AdapterPool.getDBAdapter();
        UserBean returnValue = null;
        
        UserBean dbBean = adapter.getUser(username);
        
        if (dbBean != null && dbBean.getPassword().equals(SecurityUtils.makeMD5Checksum(password)))
        {
            returnValue =  dbBean;
        }
        
        AdapterPool.releaseDBAdapter(adapter);
        return returnValue;
    }

    /**
     * Ändert das Passwort eines Users falls der übergebene Username
     * und das Passwort stimmen.
     * 
     * @param username Username des zu ändernden Users
     * @param password Altes Passwort das geändert werden soll
     * @param newPassword Neues Passwort
     * @return
     * @throws DBException 
     */
    public static boolean ChangeUserPWD(String username, String password, String newPassword) throws DBException
    {
        DBAdapter adapter = AdapterPool.getDBAdapter();
        boolean returnValue = false;
        
        UserBean dbBean = adapter.getUser(username);

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
}
